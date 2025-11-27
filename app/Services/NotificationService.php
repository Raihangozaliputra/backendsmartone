<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config;
use App\Notifications\FaceRecognitionFailed;
use App\Notifications\LowConfidenceAlert;
use App\Notifications\AttendanceSummary;

class NotificationService extends Service
{
    /**
     * Send face recognition failed notification.
     *
     * @param User $user
     * @param array $recognitionData
     * @return void
     */
    public function sendFaceRecognitionFailed(User $user, $recognitionData)
    {
        if (!Config::get('smartpresence.notifications.face_recognition_failed')) {
            return;
        }

        $user->notify(new FaceRecognitionFailed($recognitionData));
    }

    /**
     * Send low confidence alert notification.
     *
     * @param User $user
     * @param array $recognitionData
     * @return void
     */
    public function sendLowConfidenceAlert(User $user, $recognitionData)
    {
        if (!Config::get('smartpresence.notifications.low_confidence_alert')) {
            return;
        }

        $user->notify(new LowConfidenceAlert($recognitionData));
    }

    /**
     * Send attendance summary notification.
     *
     * @param User $user
     * @param array $attendanceData
     * @return void
     */
    public function sendAttendanceSummary(User $user, $attendanceData)
    {
        if (!Config::get('smartpresence.notifications.attendance_summary')) {
            return;
        }

        $user->notify(new AttendanceSummary($attendanceData));
    }

    /**
     * Send notification to all admins.
     *
     * @param string $notificationClass
     * @param array $data
     * @return void
     */
    public function notifyAdmins($notificationClass, $data)
    {
        $admins = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        Notification::send($admins, new $notificationClass($data));
    }

    /**
     * Send notification to all teachers.
     *
     * @param string $notificationClass
     * @param array $data
     * @return void
     */
    public function notifyTeachers($notificationClass, $data)
    {
        $teachers = User::whereHas('roles', function($query) {
            $query->where('name', 'teacher');
        })->get();

        Notification::send($teachers, new $notificationClass($data));
    }
}