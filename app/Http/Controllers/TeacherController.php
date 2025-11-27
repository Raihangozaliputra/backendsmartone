<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\AttendanceResource;
use App\Http\Requests\StoreLeaveRequestRequest;

class TeacherController extends Controller
{
    /**
     * Get teacher dashboard statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request)
    {
        $teacher = $request->user();
        
        // Get students in teacher's classrooms
        $studentsCount = $teacher->classrooms()->withCount('students')->first()->students_count ?? 0;
        
        // Get today's attendance stats
        $todayPresent = Attendance::whereDate('created_at', now()->toDateString())
            ->whereHas('user', function($query) use ($teacher) {
                $query->whereHas('classrooms', function($q) use ($teacher) {
                    $q->whereIn('classrooms.id', $teacher->classrooms->pluck('id'));
                });
            })
            ->where('status', 'present')
            ->count();
            
        return response()->json([
            'students_count' => $studentsCount,
            'today_present' => $todayPresent,
            'attendance_rate' => $studentsCount > 0 ? ($todayPresent / $studentsCount) * 100 : 0,
        ]);
    }

    /**
     * Get students in teacher's classrooms.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function students(Request $request)
    {
        $teacher = $request->user();
        
        $students = User::whereHas('roles', function($query) {
                $query->where('name', 'student');
            })
            ->whereHas('classrooms', function($query) use ($teacher) {
                $query->whereIn('classrooms.id', $teacher->classrooms->pluck('id'));
            })
            ->paginate(15);
            
        return UserResource::collection($students);
    }

    /**
     * Get attendance records for a specific student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $studentId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function studentAttendance(Request $request, $studentId)
    {
        $attendances = Attendance::where('user_id', $studentId)
            ->with(['schedule', 'schedule.classroom'])
            ->paginate(15);
            
        return AttendanceResource::collection($attendances);
    }

    /**
     * Create a leave request for a student.
     *
     * @param  \App\Http\Requests\StoreLeaveRequestRequest  $request
     * @param  int  $studentId
     * @return \App\Http\Resources\LeaveRequestResource
     */
    public function createLeaveRequest(StoreLeaveRequestRequest $request, $studentId)
    {
        $leaveRequest = LeaveRequest::create(array_merge(
            $request->validated(),
            ['user_id' => $studentId]
        ));
        
        return new \App\Http\Resources\LeaveRequestResource($leaveRequest);
    }
}