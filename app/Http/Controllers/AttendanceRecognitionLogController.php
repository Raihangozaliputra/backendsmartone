<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecognitionLog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRecognitionLogRequest;
use App\Http\Requests\UpdateAttendanceRecognitionLogRequest;
use App\Http\Resources\AttendanceRecognitionLogResource;

class AttendanceRecognitionLogController extends Controller
{
    /**
     * Display a listing of the attendance recognition logs.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $logs = AttendanceRecognitionLog::with(['user', 'attendance'])->paginate(15);
        return AttendanceRecognitionLogResource::collection($logs);
    }

    /**
     * Store a newly created attendance recognition log in storage.
     *
     * @param  \App\Http\Requests\StoreAttendanceRecognitionLogRequest  $request
     * @return \App\Http\Resources\AttendanceRecognitionLogResource
     */
    public function store(StoreAttendanceRecognitionLogRequest $request)
    {
        $log = AttendanceRecognitionLog::create($request->validated());
        return new AttendanceRecognitionLogResource($log);
    }

    /**
     * Display the specified attendance recognition log.
     *
     * @param  \App\Models\AttendanceRecognitionLog  $log
     * @return \App\Http\Resources\AttendanceRecognitionLogResource
     */
    public function show(AttendanceRecognitionLog $log)
    {
        return new AttendanceRecognitionLogResource($log->load(['user', 'attendance']));
    }

    /**
     * Update the specified attendance recognition log in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRecognitionLogRequest  $request
     * @param  \App\Models\AttendanceRecognitionLog  $log
     * @return \App\Http\Resources\AttendanceRecognitionLogResource
     */
    public function update(UpdateAttendanceRecognitionLogRequest $request, AttendanceRecognitionLog $log)
    {
        $log->update($request->validated());
        return new AttendanceRecognitionLogResource($log);
    }

    /**
     * Remove the specified attendance recognition log from storage.
     *
     * @param  \App\Models\AttendanceRecognitionLog  $log
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AttendanceRecognitionLog $log)
    {
        $log->delete();
        return response()->json(['message' => 'Attendance recognition log deleted successfully']);
    }
}