<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Resources\AttendanceResource;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Get daily attendance report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function dailyAttendance(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $attendances = $this->reportService->getDailyAttendanceReport($date);
        
        return AttendanceResource::collection($attendances);
    }

    /**
     * Get monthly recap report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlyRecap(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $report = $this->reportService->getMonthlyRecapReport($month, $year);
        
        return response()->json($report);
    }

    /**
     * Get late arrivals report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function lateArrivals(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());
        $lateAttendances = $this->reportService->getLateArrivalsReport($startDate, $endDate);
        
        return AttendanceResource::collection($lateAttendances);
    }
}