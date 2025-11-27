<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExportService extends Service
{
    /**
     * Export attendance data to CSV format.
     *
     * @param array $data
     * @param string $filename
     * @return string
     */
    public function exportToCsv($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'attendance_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        }
        
        $filepath = 'exports/' . $filename;
        
        $headers = array_keys($data[0] ?? []);
        $csv = fopen('php://temp', 'w');
        
        // Add BOM for Excel compatibility
        fputs($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Write headers
        fputcsv($csv, $headers);
        
        // Write data
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }
        
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        Storage::disk('local')->put($filepath, $csvContent);
        
        return $filepath;
    }

    /**
     * Export attendance data to JSON format.
     *
     * @param array $data
     * @param string $filename
     * @return string
     */
    public function exportToJson($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'attendance_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.json';
        }
        
        $filepath = 'exports/' . $filename;
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        
        Storage::disk('local')->put($filepath, $jsonContent);
        
        return $filepath;
    }

    /**
     * Prepare attendance data for export.
     *
     * @param \Illuminate\Database\Eloquent\Collection $attendances
     * @return array
     */
    public function prepareAttendanceData($attendances)
    {
        $data = [];
        
        foreach ($attendances as $attendance) {
            $data[] = [
                'user_name' => $attendance->user->name ?? '',
                'user_email' => $attendance->user->email ?? '',
                'date' => $attendance->created_at->format('Y-m-d'),
                'check_in_time' => $attendance->check_in_time ? $attendance->check_in_time->format('H:i:s') : '',
                'check_out_time' => $attendance->check_out_time ? $attendance->check_out_time->format('H:i:s') : '',
                'status' => $attendance->status,
                'notes' => $attendance->notes,
            ];
        }
        
        return $data;
    }

    /**
     * Generate attendance summary report.
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function generateAttendanceSummary($startDate, $endDate)
    {
        $attendances = Attendance::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();
            
        $summary = [
            'total_attendances' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
        ];
        
        return $summary;
    }

    /**
     * Export user data to CSV format.
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param string $filename
     * @return string
     */
    public function exportUsersToCsv($users, $filename = null)
    {
        if (!$filename) {
            $filename = 'users_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        }
        
        $filepath = 'exports/' . $filename;
        
        $headers = ['Name', 'Email', 'Role', 'Status', 'Created At'];
        $csv = fopen('php://temp', 'w');
        
        // Add BOM for Excel compatibility
        fputs($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Write headers
        fputcsv($csv, $headers);
        
        // Write data
        foreach ($users as $user) {
            fputcsv($csv, [
                $user->name,
                $user->email,
                $user->role,
                $user->status ? 'Active' : 'Inactive',
                $user->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        Storage::disk('local')->put($filepath, $csvContent);
        
        return $filepath;
    }
}