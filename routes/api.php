<?php

use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\auth\AdminAuthController;
use App\Http\Controllers\Admin\blogs\AdminBlogsController;

use App\Http\Controllers\admin\bookings\AdminBookingsController;
use App\Http\Controllers\Admin\bookings\AdminDigiBooksController;

// use App\Http\Controllers\admin\AdminBookController;

use App\Http\Controllers\admin\books\AdminBookController;

use App\Http\Controllers\Admin\chat\AdminChatController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use App\Http\Controllers\admin\dashboard\AdminDashboardController;

use App\Http\Controllers\general\books\PublicBooksController;
use App\Http\Controllers\student\auth\StudentAuthController;
use App\Http\Controllers\student\booking\StudentBookingController;
use App\Http\Controllers\student\chat\StudentChatController;
use App\Http\Controllers\student\dashboard\StudentDashboardController;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\general\flashcard\StudentFlashCardController;


use App\Http\Controllers\Teacher\auth\TeacherAuthController;
use App\Http\Controllers\Teacher\Booking\TeacherBookingController;
use App\Http\Controllers\Teacher\chat\TeacherChatController;
use App\Http\Controllers\teacher\ebooks\TeacherSavedPdfsController;
use App\Http\Controllers\Teacher\notes\TeacherNotesController;
use App\Http\Controllers\general\mydispute\StaffDisputeController;
use App\Http\Controllers\Teacher\dashboard\TeacherDashboardController;
use App\Http\Controllers\general\flashcard\TeacherFlashcardController;


use App\Http\Controllers\general\mydispute\MyDisputeController;
use App\Http\Controllers\general\mydispute\AdminDisputeController;
use App\Http\Controllers\student\studentReviewController\StudentReviewController;
use App\Http\Controllers\Teacher\reviewController\ReviewController;
use App\Http\Controllers\student\notes\StudentSavedNotesController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LibraryConfig\LibraryConfigController;
use App\Http\Controllers\admin\bookConfig\BookConfigController;


/////////////////*************** Public Routes **************//////////////////

Route::get('/home/loadReviews', [ReviewController::class, 'loadHomeReviews']);
Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);
Route::get('/notes/getAllPublicNotes', [TeacherNotesController::class, 'loadAllPublicNotes']);


/////////////////*************** Student Auth (Public) **************//////////////////

Route::post("/student/auth/studentRegister", [StudentAuthController::class, "studentRegister"]);
Route::post("/student/auth/studentLogin", [StudentAuthController::class, "studentLogin"]);
Route::post("/student/auth/forgotPassword", [StudentAuthController::class, "forgotPassword"]);
Route::post("/student/auth/resetPassword", [StudentAuthController::class, "resetPassword"]);


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

   
    ///////////////////***************Student Flash Cards ******************////////////////////
Route::post('/student/flashcards/fetchAllFlashCards', [StudentFlashCardController::class, 'fetchAllFlashCards']);
Route::post('/student/flashcards/deleteFlashCard',    [StudentFlashCardController::class, 'deleteFlashCard']);
Route::post('/student/flashcards/storeFlashCard', [StudentFlashCardController::class, 'storeFlashCard']);


    //////////////*******************Student Notification ******************//////////


    Route::post("/student/notification/fetchAllNotifications", [StudentNotificationController::class, "fetchAllNotifications"]);
    Route::post("/student/notification/markAllAsRead", [StudentNotificationController::class, "markAllAsRead"]);
    Route::post('/student/password/changePassword', [StudentAuthController::class, 'changePassword']);
    Route::post('/student/notification/markOneAsRead', [StudentNotificationController::class, 'markOneAsRead']);
    Route::post('/student/notification/deleteNotification', [StudentNotificationController::class, 'deleteNotification']);
    Route::post('/student/notification/deleteAllNotifications', [StudentNotificationController::class, 'deleteAllNotifications']);



    // Student Booking
    Route::post('/student/booking/newReservation', [StudentBookingController::class, 'newReservation']);
    Route::post('/student/booking/loadMyBookings', [StudentBookingController::class, 'loadMyBookings']);
    Route::post('/student/booking/fetchAllBooks', [StudentBookingController::class, 'fetchAllBooks']);

    // Student Chat Routes
    Route::post('/student/chat/store', [StudentChatController::class, 'store']);
    Route::post('/student/chat/fetchAllChats', [StudentChatController::class, 'fetchAllChats']);

    Route::post('/student/ebooks/saveEbook', [StudentSavedNotesController::class, 'saveEbook']);
      Route::delete('/student/ebooks/removeSavedEbook/{id}', [StudentSavedNotesController::class, 'removeSavedEbook']);
    Route::post('/student/smartlib-ai/chat', [StudentChatController::class, 'smartLibChat']);

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

///////////////////***************Teacher Flash Cards ******************////////////////////
Route::post('/teacher/flashcards/fetchAllFlashCards', [TeacherFlashCardController::class, 'fetchAllFlashCards']);
Route::post('/teacher/flashcards/deleteFlashCard',    [TeacherFlashCardController::class, 'deleteFlashCard']);
Route::post('/teacher/flashcards/storeFlashCard', [TeacherFlashCardController::class, 'storeFlashCard']);


    // Teacher Reviews
    Route::post('/teacher/reviews/submitReview', [ReviewController::class, 'submitReview']);
    Route::post('/teacher/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);

    // Teacher Notes
    Route::post('/teacher/notes/uploadNote', [TeacherNotesController::class, 'uploadNote']);
    Route::get('/teacher/notes/loadAllNotes', [TeacherNotesController::class, 'loadAllNotes']);
    Route::get('/teacher/notes/loadAllNotes/{teacher_id}', [TeacherNotesController::class, 'loadAllNotes']);
    Route::delete('/teacher/notes/deleteNote/{id}', [TeacherNotesController::class, 'deleteNote']);
    Route::post('/teacher/notes/updateNote/{id}', [TeacherNotesController::class, 'updateNote']);

    ///////////////////*****************Teacher Saved Ebooks / PDFs ****************//////////////////
    Route::post('/teacher/ebooks/saveEbook', [TeacherSavedPdfsController::class, 'saveEbook']);
    Route::get('/teacher/ebooks/getSavedEbooks/{teacher_id}', [TeacherSavedPdfsController::class, 'getSavedEbooks']);
    Route::delete('/teacher/ebooks/removeSavedEbook/{id}', [TeacherSavedPDFsController::class, 'removeSavedEbook']);

    ////////////////////****************** Teacher Disputes ***************/////////////////
    // Teacher Disputes
    Route::post('/teacher/disputes/fetchAllDisputes', [StaffDisputeController::class, 'fetchAllDisputes']);
    Route::post('/teacher/disputes/add', [StaffDisputeController::class, 'add']);
    Route::post('/teacher/disputes/update', [StaffDisputeController::class, 'update']);
    Route::post('/teacher/disputes/delete', [StaffDisputeController::class, 'delete']);



    ///////////////////*****************Teacher Booking ****************//////////////////
    Route::post("/teacher/booking/newReservation", [TeacherBookingController::class, "newReservation"]);
    Route::post("/teacher/booking/loadMyBookings", [TeacherBookingController::class, "loadMyBookings"]);

    ////////////////////**************Teacher Chat Routes *****************////////////////////
    Route::post('/teacher/chat/store', [TeacherChatController::class, 'store']);
    Route::post('/teacher/chat/fetchAllChats', [TeacherChatController::class, 'fetchAllChats']);

    /////////////////****************Teacher Notifications *************/////////////////////
    Route::post('/teacher/notifications/fetchAllNotifications', [TeacherNotificationController::class, 'fetchAllNotifications']);
    Route::post('/teacher/notifications/markAllAsRead', [TeacherNotificationController::class, 'markAllAsRead']);
 
    Route::post('/teacher/notifications/markOneAsRead', [TeacherNotificationController::class, 'markOneAsRead']);
    Route::post('/teacher/notifications/deleteNotification', [TeacherNotificationController::class, 'deleteNotification']);
    Route::post('/teacher/notifications/deleteAllNotifications', [TeacherNotificationController::class, 'deleteAllNotifications']);


    ///////////////////*****************Teacher Dashboard ****************//////////////////
    Route::post('/teacher/dashboard/fetchTeacherStatsForDashboard', [TeacherDashboardController::class, 'fetchTeacherStatsForDashboard']);
    // Route::post('/teacher/disputes/fetchAllDisputes',[TeacherDashboardController::class, 'fetchTeacherRecentDisputes']);
  });



/////////////////*************** Admin Auth (Public) **************//////////////////


    ///////////////////*****************Teacher Dashboard ****************//////////////////
    
Route::post('/teacher/dashboard/fetchTeacherStatsForDashboard',[TeacherDashboardController::class, 'fetchTeacherStatsForDashboard']);
Route::post('/teacher/disputes/fetchAllDisputes',[TeacherDashboardController::class, 'fetchTeacherRecentDisputes']);

/////////////////***************Admin Routes **************//////////////////

////////////////****************Admin Auth ****************//////////////////

Route::post("/admin/auth/adminRegister", [AdminAuthController::class, "adminRegister"]);
Route::post("/admin/auth/adminLogin", [AdminAuthController::class, "adminLogin"]);


/////////////////*************** Admin Protected Routes **************//////////////////

Route::middleware(['auth:Lbadmin', 'guard:admin'])->group(function () {

    Route::get("/admin/subadmin/list", [AdminAuthController::class, "loadAllSubAdmins"]);
    Route::get("/admin/auth/me", [AdminAuthController::class, "me"]);

    ////////////////**************Admin Reviews *****************///////////////////
    Route::post('/admin/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);
    Route::post('/admin/reviews/approve', [ReviewController::class, 'approveReview']);
    Route::post('/admin/reviews/reject', [ReviewController::class, 'rejectReview']);
    Route::post('/admin/reviews/delete', [ReviewController::class, 'deleteReview']);

    //////////////****************Admin Manage Users *************/////////////////////
    Route::post("/admin/teacherAuth/registerTeacher", [AdminUserController::class, "registerTeacher"]);
    Route::get("/admin/teacherAuth/loadAllTeacher", [AdminUserController::class, "loadAllTeacher"]);
    Route::get("/admin/studentAuth/loadAllStudents", [AdminUserController::class, "loadAllStudents"]);

    /////////////****************Admin Manage Books **************//////////////////
    Route::prefix('/admin/books')->group(function () {
        Route::post('/addBook', [AdminBookController::class, 'addBook']);
        Route::post('/update/{id}', [AdminBookController::class, 'updateBook']);
        Route::delete('/delete/{id}', [AdminBookController::class, 'deleteBook']);
    });   // ← books group yahin band ho jaye

    /////////////****************Library Configuration **************//////////////////
    Route::prefix('/admin/libraryConfig')->group(function () {   // ✅ ab books se bahar, admin group ke andar
        Route::post('/getLibraryConfiguration', [LibraryConfigController::class, 'getLibraryConfiguration']);
        Route::post('/updateLibraryConfiguration', [LibraryConfigController::class, 'updateLibraryConfiguration']);
    });
   /*
/*
|--------------------------------------------------------------------------
| Book Configuration
|--------------------------------------------------------------------------
*/

Route::post(
    "/admin/bookConfig/getBookConfiguration",
    [BookConfigController::class, "getBookConfiguration"]
);

Route::post(
    "/admin/bookConfig/updateBookConfiguration",
    [BookConfigController::class, "updateBookConfiguration"]
);





    //////////////////**************Admin Manage Bookings *************//////////////////
    Route::post("/admin/bookings/fetchAllBookings", [AdminBookingsController::class, "fetchAllBookings"]);
    Route::post("/admin/bookings/approveReservation", [AdminBookingsController::class, "approveReservation"]);
    Route::post("/admin/bookings/rejectReservation", [AdminBookingsController::class, "rejectReservation"]);
    Route::post("/admin/bookings/returnBook", [AdminBookingsController::class, "returnBook"]);

    ///////////////////*************Admin Notifications **************/////////////////////
    Route::post("/admin/notification/fetchAllNotifications", [AdminNotificationController::class, "fetchAllNotifications"]);
    Route::post("/admin/notification/markAllAsRead", [AdminNotificationController::class, "markAllAsRead"]);
    Route::post('/admin/notification/markOneAsRead', [AdminNotificationController::class, 'markOneAsRead']);
    Route::post('/admin/notification/deleteNotification', [AdminNotificationController::class, 'deleteNotification']);
    Route::post('/admin/notification/deleteAllNotifications', [AdminNotificationController::class, 'deleteAllNotifications']);


    /////////////////***************Admin Manage Blogs ***************//////////////////
    Route::prefix('admin/blogs')->group(function () {
        Route::get('/fetchAllBlogs', [AdminBlogsController::class, 'fetchAllBlogs']);
        Route::post('/addBlog', [AdminBlogsController::class, 'addBlog']);
        Route::post('/update/{id}', [AdminBlogsController::class, 'updateBlog']);
        Route::delete('/delete/{id}', [AdminBlogsController::class, 'deleteBlog']);
    });

    ///////////////*************Admin Chat Routes ****************///////////////
    Route::post('/admin/chat/fetchAllChats', [AdminChatController::class, 'fetchAllChats']);
    Route::post('/admin/chat/store', [AdminChatController::class, 'store']);

    /////////////***************Admin Manage eBooks *************//////////////
    Route::prefix('admin/ebooks')->group(function () {
        Route::post('upload', [AdminDigiBooksController::class, 'uploadEbook']);
        Route::get('all', [AdminDigiBooksController::class, 'loadAllEbooks']);
        Route::post('update/{id}', [AdminDigiBooksController::class, 'updateEbook']);
        Route::delete('delete/{id}', [AdminDigiBooksController::class, 'deleteEbook']);
    });

    ///////////////************* Admin Disputes ***************///////////////////
    Route::get('/admin/disputes/fetchAllDisputes', [AdminDisputeController::class, 'fetchAllDisputes']);
    Route::post('/admin/disputes/resolve', [AdminDisputeController::class, 'resolve']);
    Route::post('/admin/disputes/delete', [AdminDisputeController::class, 'delete']);});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
        ///////////////************* Admin Dashboard***************///////////////////
    Route::post('/admin/dashboard/fetchAdminStatsForDashboard',[AdminDashboardController::class, 'fetchAdminStatsForDashboard']);
    Route::post('/admin/dashboard/fetchAdminRecentDisputes',[AdminDashboardController::class, 'fetchAdminRecentDisputes']);
});