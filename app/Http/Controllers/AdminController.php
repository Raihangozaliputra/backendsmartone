<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ClassroomResource;
use App\Services\StatisticsService;

class AdminController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Get admin dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalClassrooms = Classroom::count();
        $todayAttendances = Attendance::whereDate('created_at', now()->toDateString())->count();
        
        $statistics = $this->statisticsService->getAttendanceStatistics(
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString()
        );
        
        return response()->json([
            'total_users' => $totalUsers,
            'total_classrooms' => $totalClassrooms,
            'today_attendances' => $todayAttendances,
            'monthly_statistics' => $statistics,
        ]);
    }

    /**
     * Get all users with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function users(Request $request)
    {
        $users = User::paginate(15);
        return UserResource::collection($users);
    }

    /**
     * Get all classrooms with pagination.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function classrooms(Request $request)
    {
        $classrooms = Classroom::paginate(15);
        return ClassroomResource::collection($classrooms);
    }

    /**
     * Get attendance records with filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function attendances(Request $request)
    {
        $query = Attendance::with(['user', 'schedule']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $attendances = $query->paginate(15);
        return AttendanceResource::collection($attendances);
    }

    /**
     * Get system statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        
        $statistics = $this->statisticsService->getAttendanceStatistics($startDate, $endDate);
        $trend = $this->statisticsService->getMonthlyAttendanceTrend();
        
        return response()->json([
            'statistics' => $statistics,
            'trend' => $trend,
        ]);
    }
}