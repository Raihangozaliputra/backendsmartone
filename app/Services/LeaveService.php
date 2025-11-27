<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;

class LeaveService extends Service
{
    /**
     * Get pending leave requests.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingRequests()
    {
        return LeaveRequest::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Approve a leave request.
     *
     * @param LeaveRequest $leaveRequest
     * @return LeaveRequest
     */
    public function approveRequest(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'approved']);
        return $leaveRequest;
    }

    /**
     * Reject a leave request.
     *
     * @param LeaveRequest $leaveRequest
     * @return LeaveRequest
     */
    public function rejectRequest(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'rejected']);
        return $leaveRequest;
    }

    /**
     * Check if user has overlapping leave requests.
     *
     * @param User $user
     * @param string $startDate
     * @param string $endDate
     * @param int|null $leaveRequestId
     * @return bool
     */
    public function hasOverlappingRequests(User $user, $startDate, $endDate, $leaveRequestId = null)
    {
        $query = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });
            
        if ($leaveRequestId) {
            $query->where('id', '!=', $leaveRequestId);
        }
        
        return $query->exists();
    }

    /**
     * Get leave requests for a user.
     *
     * @param User $user
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLeaveRequests(User $user, $status = null)
    {
        $query = LeaveRequest::where('user_id', $user->id);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Calculate leave days between two dates.
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function calculateLeaveDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Count only weekdays (Monday to Friday)
        $leaveDays = 0;
        $current = $start->copy();
        
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $leaveDays++;
            }
            $current->addDay();
        }
        
        return $leaveDays;
    }
}