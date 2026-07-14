<?php

namespace App\Http\Controllers\admin\bookings;

use App\Http\Controllers\Controller;
use App\Models\general\bookings\lbbooking;
use App\Models\general\notification\Lbnotification;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use App\Models\admin\bookConfig\LbBookconfig;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminBookingsController extends Controller
{
    /* ── Fetch All Bookings by status ── */
    public function fetchAllBookings(Request $request)
    {
        $bookings = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
            ->where(['status' => $request->status])
            ->get();

        // ── Auto-detect overdue bookings & notify (only for 'issued' status) ──
        if ($request->status === 'issued') {
            $today = Carbon::now()->startOfDay();

            // ── Book config se fine per day nikalein ──
            $bookConfig = LbBookconfig::first();
            $finePerDay = $bookConfig->fine_per_day ?? 10;

            foreach ($bookings as $booking) {
                if ($booking->due_date && Carbon::parse($booking->due_date)->startOfDay()->lt($today)) {

                    $dueDate     = Carbon::parse($booking->due_date)->startOfDay();
                    $overdueDays = $today->diffInDays($dueDate);
                    $bookTitle   = $booking->lbbook->title ?? 'a book';

                    $ownerColumn = $booking->lbstudent_id ? 'lbstudent_id' : 'lbteacher_id';
                    $ownerId     = $booking->lbstudent_id ?: $booking->lbteacher_id;

                    $alreadyNotified = Lbnotification::where($ownerColumn, $ownerId)
                        ->where('title', 'Book Overdue')
                        ->where('subtitle', 'like', "%{$bookTitle}%")
                        ->exists();

                    if (!$alreadyNotified) {

                        $ownerName  = 'User';
                        $ownerEmail = '—';

                        if ($booking->lbstudent_id) {
                            $ownerName  = $booking->lbstudent->fullName ?? 'Student';
                            $ownerEmail = $booking->lbstudent->email ?? '—';

                            StudentNotificationController::notifyStudent(
                                $booking->lbstudent_id,
                                'Book Overdue',
                                "\"$bookTitle\" is overdue. Please return it as soon as possible.",
                                'overdue',
                                [
                                    'title'          => $bookTitle,
                                    'subtitle'       => 'Due: ' . $booking->due_date,
                                    'daysOverdue'    => $overdueDays,
                                    'fine'           => 'Rs. ' . ($overdueDays * $finePerDay),
                                    'libraryContact' => 'library@ggcwlibrary.edu.pk',
                                    'action'         => 'Please return the book immediately to avoid additional fines.',
                                ]
                            );
                        } elseif ($booking->lbteacher_id) {
                            $ownerName  = $booking->lbteacher->name ?? 'Teacher';
                            $ownerEmail = $booking->lbteacher->email ?? '—';

                            TeacherNotificationController::notifyTeacher(
                                $booking->lbteacher_id,
                                'Book Overdue',
                                "\"$bookTitle\" is overdue. Please return it as soon as possible.",
                                'overdue',
                                [
                                    'student'      => $ownerName,
                                    'contactEmail' => $ownerEmail,
                                    'bookTitle'    => $bookTitle,
                                    'dueDate'      => $booking->due_date,
                                    'daysOverdue'  => $overdueDays,
                                    'fine'         => '—',
                                    'action'       => 'Please return the book as soon as possible.',
                                ]
                            );
                        }

                        AdminNotificationController::notifyAllAdmins(
                            'Book Overdue',
                            "\"$bookTitle\" issued to $ownerName is now overdue.",
                            'overdue',
                            [
                                'student'      => $ownerName,
                                'contactEmail' => $ownerEmail,
                                'bookTitle'    => $bookTitle,
                                'dueDate'      => $booking->due_date,
                                'daysOverdue'  => $overdueDays,
                                'fine'         => $booking->lbstudent_id ? ('Rs. ' . ($overdueDays * $finePerDay)) : '—',
                                'action'       => 'Contact the borrower and follow up on the overdue book.',
                            ]
                        );
                    }
                }
            }
        }

        if ($bookings) {
            return response()->json(["success" => true, "bookings" => $bookings]);
        } else {
            return response()->json(["success" => false, "message" => "No bookings yet!"]);
        }
    }

    /* ── Approve Reservation → status = issued ── */
    public function approveReservation(Request $request)
    {
        try {
            $booking = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            // ── Book config se max issue days nikalein ──
            $bookConfig = LbBookconfig::first();

            if (!$bookConfig) {
                return response()->json([
                    "success" => false,
                    "message" => "Book configuration not set. Please contact admin."
                ]);
            }

            $maxIssueDays = $bookConfig->max_issue_days;

            $today   = Carbon::now()->toDateString();
            $dueDate = Carbon::now()->addDays($maxIssueDays)->toDateString();

            $booking->status     = "issued";
            $booking->issue_date = $today;
            $booking->due_date   = $dueDate;
            $booking->save();

            $bookTitle = $booking->lbbook->title ?? 'a book';
            $ownerName = $booking->lbstudent->fullName ?? ($booking->lbteacher->name ?? 'a user');

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Book Issued',
                    "\"$bookTitle\" has been issued to you. Due date: $dueDate",
                    'book'
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Book Issued',
                    "\"$bookTitle\" has been issued to you. Due date: $dueDate",
                    'book'
                );
            }

            AdminNotificationController::notifyAllAdmins(
                'Book Issued',
                  "\"$bookTitle\" issued to $ownerName. Due date: $dueDate",
                     'book'
            );

            return response()->json([
                "success" => true,
                "message" => "Reservation approved and book issued successfully.",
                "booking" => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ── Reject Reservation → delete record ── */
    public function rejectReservation(Request $request)
    {
        try {
            $booking = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            $bookTitle = $booking->lbbook->title ?? 'the book';

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Reservation Rejected',
                    "Your reservation for \"$bookTitle\" was rejected by the library.",
                    'dispute'
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Reservation Rejected',
                    "Your reservation for \"$bookTitle\" was rejected by the library.",
                    'dispute'
                );
            }

            $booking->delete();

            return response()->json([
                "success" => true,
                "message" => "Reservation rejected and deleted successfully."
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ════════════════════════════════════════════
       Return Book — auto fine calculation
       Fine: dynamic per-day rate from LbBookconfig
    ════════════════════════════════════════════ */
    public function returnBook(Request $request)
    {
        try {
            $booking = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            // ── Book config se fine per day nikalein ──
            $bookConfig = LbBookconfig::first();

            if (!$bookConfig) {
                return response()->json([
                    "success" => false,
                    "message" => "Book configuration not set. Please contact admin."
                ]);
            }

            $finePerDay = $bookConfig->fine_per_day;

            $today   = Carbon::now()->startOfDay();
            $dueDate = Carbon::parse($booking->due_date)->startOfDay();

            $fine = 0;
            $overdueDays = 0;
            if ($today->gt($dueDate)) {
                $overdueDays = $today->diffInDays($dueDate);
                $fine        = $overdueDays * $finePerDay;
            }

            $booking->status = "returned";
            $booking->fine   = $fine > 0 ? $fine : null;
            $booking->save();

            $bookTitle = $booking->lbbook->title ?? 'the book';
            $ownerName = $booking->lbstudent->fullName ?? ($booking->lbteacher->name ?? 'a user');

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Book Returned',
                    "\"$bookTitle\" has been marked as returned.",
                    'book'
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Book Returned',
                    "\"$bookTitle\" has been marked as returned.",
                    'book'
                );
            }

            if ($fine > 0) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Fine Issued',
                    "You have a fine of Rs. $fine for returning \"$bookTitle\" late ($overdueDays days overdue).",
                    'fine',
                    [
                        'fineAmount' => 'Rs. ' . $fine,
                        'reason'     => "Late return of \"$bookTitle\" ($overdueDays days overdue)",
                        'issuedOn'   => Carbon::now()->toDateString(),
                        'dueBy'      => 'As soon as possible',
                        'action'     => 'Please pay the fine to clear your account.',
                    ]
                );

                AdminNotificationController::notifyAllAdmins(
                   'Fine Issued',
                     "Rs. $fine fine issued to $ownerName for late return of \"$bookTitle\".",
                      'fine'
                );
            }

            return response()->json([
                "success"      => true,
                "message"      => "Book returned successfully.",
                "fine"         => $fine,
                "overdue_days" => $overdueDays,
                "booking"      => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
    /* ════════════════════════════════════════════
       Mark Book as Lost — dynamic fine from LbBookconfig
    ════════════════════════════════════════════ */
    public function markAsLost(Request $request)
    {
        try {
            $booking = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            // ── Book config se lost book fine nikalein ──
            $bookConfig = LbBookconfig::first();

            if (!$bookConfig) {
                return response()->json([
                    "success" => false,
                    "message" => "Book configuration not set. Please contact admin."
                ]);
            }

            $lostFine = $bookConfig->lost_book_fine;

            $booking->status = "lost";
            $booking->fine   = $lostFine;
            $booking->save();

            $bookTitle = $booking->lbbook->title ?? 'the book';
            $ownerName = $booking->lbstudent->fullName ?? ($booking->lbteacher->name ?? 'a user');

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Book Marked as Lost',
                    "\"$bookTitle\" has been marked as lost. A fine of Rs. $lostFine has been applied to your account.",
                    'fine',
                    [
                        'fineAmount' => 'Rs. ' . $lostFine,
                        'reason'     => "\"$bookTitle\" marked as lost",
                        'issuedOn'   => Carbon::now()->toDateString(),
                        'dueBy'      => 'As soon as possible',
                        'action'     => 'Please pay the fine to clear your account.',
                    ]
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Book Marked as Lost',
                    "\"$bookTitle\" has been marked as lost. A fine of Rs. $lostFine has been applied to your account.",
                    'fine'
                );
            }

            AdminNotificationController::notifyAllAdmins(
                'Book Marked as Lost',
                "\"$bookTitle\" marked as lost for $ownerName. Rs. $lostFine fine applied.",
                'fine'
            );

            return response()->json([
                "success" => true,
                "message" => "Book marked as lost and fine applied successfully.",
                "fine"    => $lostFine,
                "booking" => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /* ════════════════════════════════════════════
       Mark Book as Damaged — dynamic fine from LbBookconfig
    ════════════════════════════════════════════ */
    public function markAsDamaged(Request $request)
    {
        try {
            $booking = lbbooking::with('lbstudent', 'lbteacher', 'lbbook')
                ->where('id', $request->booking_id)
                ->first();

            if (!$booking) {
                return response()->json(["success" => false, "message" => "Booking not found."]);
            }

            // ── Book config se damaged book fine nikalein ──
            $bookConfig = LbBookconfig::first();

            if (!$bookConfig) {
                return response()->json([
                    "success" => false,
                    "message" => "Book configuration not set. Please contact admin."
                ]);
            }

            $damagedFine = $bookConfig->damaged_book_fine;

            $booking->status = "damaged";
            $booking->fine   = $damagedFine;
            $booking->save();

            $bookTitle = $booking->lbbook->title ?? 'the book';
            $ownerName = $booking->lbstudent->fullName ?? ($booking->lbteacher->name ?? 'a user');

            if ($booking->lbstudent_id) {
                StudentNotificationController::notifyStudent(
                    $booking->lbstudent_id,
                    'Book Marked as Damaged',
                    "\"$bookTitle\" has been marked as damaged. A fine of Rs. $damagedFine has been applied to your account.",
                    'fine',
                    [
                        'fineAmount' => 'Rs. ' . $damagedFine,
                        'reason'     => "\"$bookTitle\" marked as damaged",
                        'issuedOn'   => Carbon::now()->toDateString(),
                        'dueBy'      => 'As soon as possible',
                        'action'     => 'Please pay the fine to clear your account.',
                    ]
                );
            } elseif ($booking->lbteacher_id) {
                TeacherNotificationController::notifyTeacher(
                    $booking->lbteacher_id,
                    'Book Marked as Damaged',
                    "\"$bookTitle\" has been marked as damaged. A fine of Rs. $damagedFine has been applied to your account.",
                    'fine'
                );
            }

            AdminNotificationController::notifyAllAdmins(
                'Book Marked as Damaged',
                "\"$bookTitle\" marked as damaged for $ownerName. Rs. $damagedFine fine applied.",
                'fine'
            );

            return response()->json([
                "success" => true,
                "message" => "Book marked as damaged and fine applied successfully.",
                "fine"    => $damagedFine,
                "booking" => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }
}