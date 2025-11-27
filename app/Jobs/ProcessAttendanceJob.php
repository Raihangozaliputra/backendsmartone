<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Attendance;
use App\Models\AttendanceRecognitionLog;
use App\Services\AttendanceService;

class ProcessAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recognitionData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recognitionData)
    {
        $this->recognitionData = $recognitionData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AttendanceService $attendanceService)
    {
        // Process the facial recognition data and create/update attendance record
        $attendance = $attendanceService->processRecognition($this->recognitionData);
        
        // Log the recognition attempt
        $log = AttendanceRecognitionLog::create([
            'user_id' => $this->recognitionData['user_id'],
            'attendance_id' => $attendance->id,
            'confidence_score' => $this->recognitionData['confidence_score'],
            'status' => $this->recognitionData['confidence_score'] > config('smartpresence.attendance.confidence_threshold') ? 'success' : 'failed',
            'raw_response' => $this->recognitionData['raw_response'],
        ]);
    }
}