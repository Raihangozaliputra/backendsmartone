<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class ScheduleService extends Service
{
    /**
     * Get today's schedule for a user.
     *
     * @param User $user
     * @return Schedule|null
     */
    public function getTodaysSchedule(User $user)
    {
        $dayOfWeek = strtolower(Carbon::now()->format('l'));
        
        return Schedule::where('user_id', $user->id)
            ->where('day_of_week', $dayOfWeek)
            ->first();
    }

    /**
     * Get this week's schedule for a user.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWeeklySchedule(User $user)
    {
        return Schedule::where('user_id', $user->id)
            ->orderBy('day_of_week')
            ->get();
    }

    /**
     * Check if user has a schedule for today.
     *
     * @param User $user
     * @return bool
     */
    public function hasTodaysSchedule(User $user)
    {
        return $this->getTodaysSchedule($user) !== null;
    }

    /**
     * Get users with schedules for today.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersWithTodaysSchedule()
    {
        $dayOfWeek = strtolower(Carbon::now()->format('l'));
        
        return User::whereHas('schedules', function($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek);
        })->get();
    }

    /**
     * Check if schedule conflicts with existing schedules.
     *
     * @param int $userId
     * @param string $dayOfWeek
     * @param string $startTime
     * @param string $endTime
     * @param int|null $scheduleId
     * @return bool
     */
    public function hasScheduleConflict($userId, $dayOfWeek, $startTime, $endTime, $scheduleId = null)
    {
        $query = Schedule::where('user_id', $userId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });
            
        if ($scheduleId) {
            $query->where('id', '!=', $scheduleId);
        }
        
        return $query->exists();
    }
}