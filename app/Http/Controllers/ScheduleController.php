<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $schedules = Schedule::paginate(15);
        return ScheduleResource::collection($schedules);
    }

    /**
     * Store a newly created schedule in storage.
     *
     * @param  \App\Http\Requests\StoreScheduleRequest  $request
     * @return \App\Http\Resources\ScheduleResource
     */
    public function store(StoreScheduleRequest $request)
    {
        $schedule = Schedule::create($request->validated());
        return new ScheduleResource($schedule);
    }

    /**
     * Display the specified schedule.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \App\Http\Resources\ScheduleResource
     */
    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified schedule in storage.
     *
     * @param  \App\Http\Requests\UpdateScheduleRequest  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \App\Http\Resources\ScheduleResource
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($request->validated());
        return new ScheduleResource($schedule);
    }

    /**
     * Remove the specified schedule from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}