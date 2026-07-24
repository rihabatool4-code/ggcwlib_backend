<?php

namespace App\Services;

use App\Models\general\bookings\lbbooking;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use Carbon\Carbon;

class ReservationExpiryService
{
    /**
     * 24 hours se purani "reserved" bookings ko "expired" mark karta hai.
     * Ye method admin/student/teacher teeno jagah se call hoga taake
     * scheduler/cron na chalne ki soorat mein bhi data sahi rahe.
     */
    public static function expireOldReservations()
    {
        $cutoff = Carbon::now()->subHours(24);

        $expiredBookings = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
            ->where('status', 'reserved')
            ->where('created_at', '<=', $cutoff)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->status = 'expired';
            $booking->save();

            $bookTitle = $booking->lbbook->title ?? 'the book';
            $ownerName = $booking->lbstudent->fullName ?? ($booking->lbteacher->name ?? 'a user');

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Reservation Expired',
                    "Your reservation for \"$bookTitle\" expired after 24 hours and has been cancelled.",
                    'dispute'
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Reservation Expired',
                    "Your reservation for \"$bookTitle\" expired after 24 hours and has been cancelled.",
                    'dispute'
                );
            }

            AdminNotificationController::notifyAllAdmins(
                'Reservation Expired',
                "Reservation for \"$bookTitle\" by $ownerName expired after 24 hours (auto-cancelled).",
                'dispute'
            );
        }

        return $expiredBookings->count();
    }
}