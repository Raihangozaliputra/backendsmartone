<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestRequest;
use App\Http\Resources\LeaveRequestResource;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the leave requests.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $leaveRequests = LeaveRequest::paginate(15);
        return LeaveRequestResource::collection($leaveRequests);
    }

    /**
     * Store a newly created leave request in storage.
     *
     * @param  \App\Http\Requests\StoreLeaveRequestRequest  $request
     * @return \App\Http\Resources\LeaveRequestResource
     */
    public function store(StoreLeaveRequestRequest $request)
    {
        $leaveRequest = LeaveRequest::create($request->validated());
        return new LeaveRequestResource($leaveRequest);
    }

    /**
     * Display the specified leave request.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \App\Http\Resources\LeaveRequestResource
     */
    public function show(LeaveRequest $leaveRequest)
    {
        return new LeaveRequestResource($leaveRequest);
    }

    /**
     * Update the specified leave request in storage.
     *
     * @param  \App\Http\Requests\UpdateLeaveRequestRequest  $request
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \App\Http\Resources\LeaveRequestResource
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update($request->validated());
        return new LeaveRequestResource($leaveRequest);
    }

    /**
     * Remove the specified leave request from storage.
     *
     * @param  \App\Models\LeaveRequest  $leaveRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return response()->json(['message' => 'Leave request deleted successfully']);
    }
}