<?php

use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\auth\AdminAuthController;
use App\Http\Controllers\Admin\blogs\AdminBlogsController;
use App\Http\Controllers\admin\bookings\AdminBookingsController;
use App\Http\Controllers\Admin\bookings\AdminDigiBooksController;
// use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\Admin\chat\AdminChatController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use App\Http\Controllers\admin\dashboard\AdminDashboardController;

use App\Http\Controllers\general\books\PublicBooksController;
use App\Http\Controllers\student\auth\StudentAuthController;
use App\Http\Controllers\student\booking\StudentBookingController;
use App\Http\Controllers\student\chat\StudentChatController;
use App\Http\Controllers\student\dashboard\StudentDashboardController;
use App\Http\Controllers\student\notification\StudentNotificationController;

use App\Http\Controllers\Teacher\auth\TeacherAuthController;
use App\Http\Controllers\Teacher\Booking\TeacherBookingController;
use App\Http\Controllers\Teacher\chat\TeacherChatController;
use App\Http\Controllers\Teacher\notes\TeacherNotesController;
use App\Http\Controllers\general\mydispute\StaffDisputeController;
use App\Http\Controllers\Teacher\dashboard\TeacherDashboardController;

use App\Http\Controllers\general\mydispute\MyDisputeController;
use App\Http\Controllers\general\mydispute\AdminDisputeController;
use App\Http\Controllers\student\studentReviewController\StudentReviewController;
use App\Http\Controllers\Teacher\reviewController\ReviewController;
use App\Http\Controllers\student\notes\StudentSavedNotesController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/////////////////*************** Public Routes **************//////////////////

Route::get('/home/loadReviews', [ReviewController::class, 'loadHomeReviews']);
Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);
Route::get('/notes/getAllPublicNotes', [TeacherNotesController::class, 'loadAllPublicNotes']);


/////////////////*************** Student Auth (Public) **************//////////////////

Route::post("/student/auth/studentRegister", [StudentAuthController::class, "studentRegister"]);
Route::post("/student/auth/studentLogin", [StudentAuthController::class, "studentLogin"]);


/////////////////*************** Student Protected Routes **************//////////////////

Route::middleware(['auth:Lbstudent', 'guard:student'])->group(function () {

    // Profile & Password
    Route::post('/student/updateProfile', [StudentAuthController::class, 'updateProfile']);
    Route::post('/student/password/changePassword', [StudentAuthController::class, 'changePassword']);

    // Student Dashboard
    Route::post('/student/dashboard/fetchStudentStatsForDashboard', [StudentDashboardController::class, 'fetchStudentStatsForDashboard']);
    Route::post('/student/disputes/fetchAllDisputes', [StudentDashboardController::class, 'fetchStudentRecentDisputes']);

    // Student Notification
    Route::post("/student/notification/fetchAllNotifications", [StudentNotificationController::class, "fetchAllNotifications"]);
    Route::post("/student/notification/markAllAsRead", [StudentNotificationController::class, "markAllAsRead"]);

    // Student Booking
    Route::post('/student/booking/newReservation', [StudentBookingController::class, 'newReservation']);
    Route::post('/student/booking/loadMyBookings', [StudentBookingController::class, 'loadMyBookings']);
    Route::post('/student/booking/fetchAllBooks', [StudentBookingController::class, 'fetchAllBooks']);

    // Student Chat Routes
    Route::post('/student/chat/store', [StudentChatController::class, 'store']);
    Route::post('/student/chat/fetchAllChats', [StudentChatController::class, 'fetchAllChats']);

    // Student Review
    Route::post('/student/reviews/loadAllReviews', [StudentReviewController::class, 'loadAllReviews']);
    Route::post('/student/reviews/submitReview', [StudentReviewController::class, 'submitReview']);

    // Student Notes
    Route::post('/student/notes/saveNote', [StudentSavedNotesController::class, 'saveNote']);
    Route::get('/student/notes/getSavedNotes/{student_id}', [StudentSavedNotesController::class, 'getSavedNotes']);
    Route::delete('/student/notes/removeSavedNote/{id}', [StudentSavedNotesController::class, 'removeSavedNote']);

    // Student Disputes
    Route::get('/mydisputes', [MyDisputeController::class, 'index']);
    Route::post('/mydisputes', [MyDisputeController::class, 'store']);
    Route::post('/student/disputes/fetchAllDisputes', [MyDisputeController::class, 'fetchAllDisputes']);
    Route::put('/mydisputes/{id}', [MyDisputeController::class, 'update']);
    Route::delete('/mydisputes/{id}', [MyDisputeController::class, 'destroy']);
});


/////////////////*************** Teacher Auth (Public) **************//////////////////

Route::post("/teacher/auth/teacherLogin", [TeacherAuthController::class, 'teacherLogin']);
Route::post("/Teacher/auth/registerteacher", [TeacherAuthController::class, "registerteacher"]);


/////////////////*************** Teacher Protected Routes **************//////////////////

Route::middleware(['auth:Lbteacher', 'guard:teacher'])->group(function () {

    // Profile & Password
    Route::post('/teacher/updateProfile', [TeacherAuthController::class, 'updateProfile']);
    Route::post('/teacher/password/changePassword', [TeacherAuthController::class, 'changePassword']);

    // Teacher Reviews
    Route::post('/teacher/reviews/submitReview', [ReviewController::class, 'submitReview']);
    Route::post('/teacher/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);

    // Teacher Notes
    Route::post('/teacher/notes/uploadNote', [TeacherNotesController::class, 'uploadNote']);
    Route::get('/teacher/notes/loadAllNotes', [TeacherNotesController::class, 'loadAllNotes']);
    Route::get('/teacher/notes/loadAllNotes/{teacher_id}', [TeacherNotesController::class, 'loadAllNotes']);
    Route::delete('/teacher/notes/deleteNote/{id}', [TeacherNotesController::class, 'deleteNote']);
    Route::post('/teacher/notes/updateNote/{id}', [TeacherNotesController::class, 'updateNote']);

    // Teacher Disputes
    Route::post('/teacher/disputes/fetchAllDisputes', [StaffDisputeController::class, 'fetchAllDisputes']);
    Route::post('/teacher/disputes/add', [StaffDisputeController::class, 'add']);
    Route::post('/teacher/disputes/update', [StaffDisputeController::class, 'update']);
    Route::post('/teacher/disputes/delete', [StaffDisputeController::class, 'delete']);
});