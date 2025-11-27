<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FaceRecognitionService;
use App\Services\AttendanceService;
use App\Services\NotificationService;
use App\Jobs\ProcessAttendanceJob;
use App\Models\AttendanceRecognitionLog;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

class AttendanceRecognitionController extends Controller
{
    protected $faceRecognitionService;
    protected $attendanceService;
    protected $notificationService;

    public function __construct(
        FaceRecognitionService $faceRecognitionService,
        AttendanceService $attendanceService,
        NotificationService $notificationService
    ) {
        $this->faceRecognitionService = $faceRecognitionService;
        $this->attendanceService = $attendanceService;
        $this->notificationService = $notificationService;
    }

    /**
     * Process facial recognition and create attendance record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
        ]);

        // Store the uploaded image temporarily
        $imagePath = $request->file('image')->store('temp', 'public');

        // Process facial recognition
        $recognitionResult = $this->faceRecognitionService->recognizeFace($imagePath);

        // Add latitude and longitude to recognition result if provided
        if ($request->has('latitude') && $request->has('longitude')) {
            $recognitionResult['latitude'] = $request->latitude;
            $recognitionResult['longitude'] = $request->longitude;
        }

        // Log the recognition attempt
        $log = AttendanceRecognitionLog::create([
            'user_id' => $recognitionResult['user_id'] ?? null,
            'attendance_id' => null,
            'confidence_score' => $recognitionResult['confidence_score'],
            'status' => (!$recognitionResult['recognized'] || 
                        $recognitionResult['confidence_score'] < Config::get('smartpresence.attendance.confidence_threshold')) ? 'failed' : 'pending',
            'raw_response' => $recognitionResult,
        ]);

        // If recognition failed or confidence is too low
        if (!$recognitionResult['recognized'] || 
            $recognitionResult['confidence_score'] < Config::get('smartpresence.attendance.confidence_threshold')) {
            
            // Notify user about failed recognition
            if ($recognitionResult['user_id']) {
                $user = User::find($recognitionResult['user_id']);
                if ($user) {
                    $this->notificationService->sendFaceRecognitionFailed($user, $recognitionResult);
                }
            }
            
            // Clean up temporary image
            Storage::disk('public')->delete($imagePath);
            
            return Response::json([
                'message' => 'Face recognition failed',
                'confidence_score' => $recognitionResult['confidence_score'],
            ], 422);
        }

        // Add user_id and confidence_score to the recognition result
        $recognitionResult['user_id'] = $recognitionResult['user_id'];
        $recognitionResult['confidence_score'] = $recognitionResult['confidence_score'];
        $recognitionResult['image_path'] = $imagePath;

        // Dispatch job to process attendance asynchronously
        ProcessAttendanceJob::dispatch($recognitionResult);

        return Response::json([
            'message' => 'Recognition successful, attendance processing in background',
            'confidence_score' => $recognitionResult['confidence_score'],
        ]);
    }

    /**
     * Verify attendance manually (admin function).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
        ]);

        // Store the uploaded image
        $imagePath = $request->file('image')->store('attendance-verification', 'public');

        // Process facial recognition
        $recognitionResult = $this->faceRecognitionService->recognizeFace($imagePath);

        // Add latitude and longitude to recognition result if provided
        if ($request->has('latitude') && $request->has('longitude')) {
            $recognitionResult['latitude'] = $request->latitude;
            $recognitionResult['longitude'] = $request->longitude;
        }

        // Clean up temporary image
        Storage::disk('public')->delete($imagePath);

        return Response::json([
            'recognized' => $recognitionResult['recognized'],
            'confidence_score' => $recognitionResult['confidence_score'],
            'user_id' => $request->user_id,
        ]);
    }
}