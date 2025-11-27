<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService extends Service
{
    /**
     * Get attendance statistics for a given period.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAttendanceStatistics($startDate, $endDate)
    {
        $attendances = Attendance::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        
        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'attendance_rate' => $total > 0 ? round(($present + $late) / $total * 100, 2) : 0,
        ];
    }

    /**
     * Get monthly attendance trend.
     *
     * @param int $months
     * @return array
     */
    public function getMonthlyAttendanceTrend($months = 6)
    {
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subMonths($months);
        
        $trend = Attendance::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present'),
            DB::raw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late'),
            DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        ->orderBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        ->get();
        
        return $trend->toArray();
    }

    /**
     * Get top late arrivals.
     *
     * @param int $limit
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getTopLateArrivals($limit = 10, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->startOfMonth();
        }
        
        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $lateArrivals = Attendance::with('user')
            ->where('status', 'late')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('user_id', DB::raw('COUNT(*) as late_count'))
            ->groupBy('user_id')
            ->orderBy('late_count', 'desc')
            ->limit($limit)
            ->get();
            
        return $lateArrivals->toArray();
    }

    /**
     * Get user attendance statistics.
     *
     * @param User $user
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getUserAttendanceStatistics(User $user, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->startOfMonth();
        }
        
        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        
        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'attendance_rate' => $total > 0 ? round(($present + $late) / $total * 100, 2) : 0,
        ];
    }

    /**
     * Get classroom attendance statistics.
     *
     * @param int $classroomId
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getClassroomAttendanceStatistics($classroomId, $startDate = null, $endDate = null)
    {
        if (!$startDate) {
            $startDate = Carbon::now()->startOfMonth();
        }
        
        if (!$endDate) {
            $endDate = Carbon::now()->endOfMonth();
        }
        
        $attendances = Attendance::whereHas('schedule', function($query) use ($classroomId) {
            $query->where('classroom_id', $classroomId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->get();
        
        $total = $attendances->count();
        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        
        return [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'attendance_rate' => $total > 0 ? round(($present + $late) / $total * 100, 2) : 0,
        ];
    }
}