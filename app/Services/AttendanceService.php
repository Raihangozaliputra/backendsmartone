<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use App\Services\GeoLocationService;
use Illuminate\Support\Facades\Log;

class AttendanceService
{
    protected $geoLocationService;

    public function __construct(GeoLocationService $geoLocationService)
    {
        $this->geoLocationService = $geoLocationService;
    }

    /**
     * Process facial recognition data and create/update attendance record.
     *
     * @param array $recognitionData
     * @return Attendance
     */
    public function processRecognition($recognitionData)
    {
        $userId = $recognitionData['user_id'];
        $confidenceScore = $recognitionData['confidence_score'];
        
        // Get today's schedule for the user
        $schedule = Schedule::where('user_id', $userId)
            ->where('day_of_week', strtolower(Carbon::now()->format('l')))
            ->first();
            
        if (!$schedule) {
            throw new \Exception('No schedule found for user today');
        }
        
        // Check if attendance record already exists for today
        $attendance = Attendance::where('user_id', $userId)
            ->where('schedule_id', $schedule->id)
            ->whereDate('created_at', Carbon::today())
            ->first();
            
        $currentTime = Carbon::now();
        
        if (!$attendance) {
            // Create new attendance record
            $attendance = new Attendance();
            $attendance->user_id = $userId;
            $attendance->schedule_id = $schedule->id;
            
            // Determine if user is late
            $scheduleStartTime = Carbon::parse($schedule->start_time);
            $isLate = $currentTime->gt($scheduleStartTime);
            
            $attendance->status = $isLate ? 'late' : 'present';
            $attendance->check_in_time = $currentTime;
            
            // Validate location if provided
            if (isset($recognitionData['latitude']) && isset($recognitionData['longitude'])) {
                $latitude = $recognitionData['latitude'];
                $longitude = $recognitionData['longitude'];
                
                // Validate coordinates format
                if ($this->geoLocationService->validateCoordinates($latitude, $longitude)) {
                    // Check if within school boundaries
                    if ($this->geoLocationService->isWithinSchoolBoundaries($latitude, $longitude)) {
                        $attendance->latitude = $latitude;
                        $attendance->longitude = $longitude;
                    } else {
                        // Log warning about location being outside school boundaries
                        Log::warning("Attendance location outside school boundaries for user ID: {$userId}");
                    }
                }
            }
        } else {
            // Update existing attendance record (check out)
            $attendance->check_out_time = $currentTime;
            
            // Validate location if provided
            if (isset($recognitionData['latitude']) && isset($recognitionData['longitude'])) {
                $latitude = $recognitionData['latitude'];
                $longitude = $recognitionData['longitude'];
                
                // Validate coordinates format
                if ($this->geoLocationService->validateCoordinates($latitude, $longitude)) {
                    // Check if within school boundaries
                    if ($this->geoLocationService->isWithinSchoolBoundaries($latitude, $longitude)) {
                        $attendance->latitude = $latitude;
                        $attendance->longitude = $longitude;
                    } else {
                        // Log warning about location being outside school boundaries
                        Log::warning("Attendance location outside school boundaries for user ID: {$userId}");
                    }
                }
            }
        }
        
        $attendance->save();
        
        return $attendance;
    }
    
    /**
     * Calculate if user is late based on schedule and check-in time.
     *
     * @param Schedule $schedule
     * @param Carbon $checkInTime
     * @return bool
     */
    public function isLate(Schedule $schedule, Carbon $checkInTime)
    {
        $scheduleStartTime = Carbon::parse($schedule->start_time);
        return $checkInTime->gt($scheduleStartTime);
    }
}