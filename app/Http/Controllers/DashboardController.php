<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Services\StatisticsService;

class DashboardController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    /**
     * Get dashboard statistics for all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
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
     * Get user-specific dashboard data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userDashboard(Request $request)
    {
        $user = $request->user();
        
        $userStatistics = $this->statisticsService->getUserAttendanceStatistics($user);
        
        $recentAttendances = Attendance::where('user_id', $user->id)
            ->with(['schedule', 'schedule.classroom'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'user' => $user,
            'statistics' => $userStatistics,
            'recent_attendances' => $recentAttendances,
        ]);
    }
}