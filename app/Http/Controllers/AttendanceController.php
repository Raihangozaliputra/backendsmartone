<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRecognitionLog;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Jobs\ProcessAttendanceJob;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $attendances = Attendance::paginate(15);
        return AttendanceResource::collection($attendances);
    }

    /**
     * Store a newly created attendance in storage.
     *
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \App\Http\Resources\AttendanceResource
     */
    public function store(StoreAttendanceRequest $request)
    {
        $attendance = Attendance::create($request->validated());
        return new AttendanceResource($attendance);
    }

    /**
     * Display the specified attendance.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \App\Http\Resources\AttendanceResource
     */
    public function show(Attendance $attendance)
    {
        return new AttendanceResource($attendance);
    }

    /**
     * Update the specified attendance in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \App\Http\Resources\AttendanceResource
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update($request->validated());
        return new AttendanceResource($attendance);
    }

    /**
     * Remove the specified attendance from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(['message' => 'Attendance record deleted successfully']);
    }

    /**
     * Process facial recognition result and create attendance record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processRecognition(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'confidence_score' => 'required|numeric|min:0|max:1',
            'raw_response' => 'required|array',
        ]);

        // Dispatch job to process attendance asynchronously
        ProcessAttendanceJob::dispatch($request->all());

        return response()->json(['message' => 'Recognition data received and queued for processing']);
    }
}