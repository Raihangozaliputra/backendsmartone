<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get daily attendance report.
     *
     * @param string $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDailyAttendanceReport($date)
    {
        return Attendance::with(['user', 'schedule'])
            ->whereDate('created_at', $date)
            ->get();
    }

    /**
     * Get monthly recap report.
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getMonthlyRecapReport($month, $year)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $totalUsers = User::count();
        $attendanceStats = Attendance::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return [
            'month' => $month,
            'year' => $year,
            'total_users' => $totalUsers,
            'attendance_stats' => $attendanceStats,
            'attendance_percentage' => $totalUsers > 0 ? 
                (isset($attendanceStats['present']) ? ($attendanceStats['present'] / $totalUsers) * 100 : 0) : 0,
        ];
    }

    /**
     * Get late arrivals report.
     *
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLateArrivalsReport($startDate, $endDate)
    {
        return Attendance::with(['user', 'schedule'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'late')
            ->get();
    }
}