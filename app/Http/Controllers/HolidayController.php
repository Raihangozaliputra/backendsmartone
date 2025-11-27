<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Http\Resources\HolidayResource;

class HolidayController extends Controller
{
    /**
     * Display a listing of the holidays.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $holidays = Holiday::paginate(15);
        return HolidayResource::collection($holidays);
    }

    /**
     * Store a newly created holiday in storage.
     *
     * @param  \App\Http\Requests\StoreHolidayRequest  $request
     * @return \App\Http\Resources\HolidayResource
     */
    public function store(StoreHolidayRequest $request)
    {
        $holiday = Holiday::create($request->validated());
        return new HolidayResource($holiday);
    }

    /**
     * Display the specified holiday.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \App\Http\Resources\HolidayResource
     */
    public function show(Holiday $holiday)
    {
        return new HolidayResource($holiday);
    }

    /**
     * Update the specified holiday in storage.
     *
     * @param  \App\Http\Requests\UpdateHolidayRequest  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \App\Http\Resources\HolidayResource
     */
    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $holiday->update($request->validated());
        return new HolidayResource($holiday);
    }

    /**
     * Remove the specified holiday from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return response()->json(['message' => 'Holiday deleted successfully']);
    }
}