<?php

use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\auth\AdminAuthController;
use App\Http\Controllers\Admin\blogs\AdminBlogsController;
use App\Http\Controllers\admin\bookings\AdminBookingsController;
use App\Http\Controllers\Admin\bookings\AdminDigiBooksController;
use App\Http\Controllers\Admin\chat\AdminChatController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
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
use App\Http\Controllers\general\mydispute\MyDisputeController;
use App\Http\Controllers\general\mydispute\AdminDisputeController;
use App\Http\Controllers\student\studentReviewController\StudentReviewController;
use App\Http\Controllers\Teacher\reviewController\ReviewController;
use App\Http\Controllers\student\notes\StudentSavedNotesController;
use App\Http\Controllers\Teacher\notifications\TeacherNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




/////////////////***************Student Routes **************//////////////////

////////////////****************Student Auth  ***************//////////////////
Route::post("/student/auth/studentRegister", [StudentAuthController::class, "studentRegister"]);
Route::post("/student/auth/studentLogin", [StudentAuthController::class, "studentLogin"]);


Route::middleware(['auth:Lbstudent', 'guard:student'])->group(function () {

    Route::post('/student/profile/updateProfile',[StudentAuthController::class, 'updateProfile']);
    Route::post('/student/password/changePassword' ,[StudentAuthController::class, 'changePassword']);
    Route::post('/student/notifications/updateNotifications',[StudentAuthController::class, 'updateNotifications']);
    Route::post('/student/dashboard/fetchStudentStatsForDashboard',[StudentDashboardController::class, 'fetchStudentStatsForDashboard']);

    ///////////////******************Student Booking **************////////////////
    Route::post("/student/booking/newReservation", [StudentBookingController::class, "newReservation"]);
    Route::post("/student/booking/loadMyBookings",  [StudentBookingController::class, "loadMyBookings"]); // ← ADD
    Route::post("/student/booking/fetchAllBooks", [StudentBookingController::class, "fetchAllBooks"]);

    //////////////*******************Student Notification ******************//////////
    Route::post("/student/notification/fetchAllNotifications", [StudentNotificationController::class, "fetchAllNotifications"]);
    Route::post("/student/notification/markAllAsRead", [StudentNotificationController::class, "markAllAsRead"]);


    //////////////////////*********** Student Dispute Chat Routes*************/////////////////
    // Route::get('/student/disputes/{dispute}/chats',  [StudentChatController::class, 'index']);
    Route::post('/student/chat/store', [StudentChatController::class, 'store']);
    Route::post('/student/chat/fetchAllChats', [StudentChatController::class, 'fetchAllChats']);

    ////////////////////*************Student Review ****************//////////////////
    Route::post('/student/reviews/loadAllReviews',[StudentReviewController::class, 'loadAllReviews']);
    Route::post( '/student/reviews/submitReview',[StudentReviewController::class, 'submitReview']);

    ///////////////////***************Student Notes ******************////////////////////
    Route::post('/student/notes/saveNote',                    [StudentSavedNotesController::class, 'saveNote']);
    Route::get('/student/notes/getSavedNotes/{student_id}',   [StudentSavedNotesController::class, 'getSavedNotes']);
    Route::delete('/student/notes/removeSavedNote/{id}',      [StudentSavedNotesController::class, 'removeSavedNote']);

    //////////////////******************Student Disputes***************/////////////////
    // NOTE: /mydisputes ko student-protected maan kar rakha hai - confirm kar dena agar ye sahi assumption nahi hai
    Route::get('/mydisputes', [MyDisputeController::class, 'index']);
    Route::post('/mydisputes', [MyDisputeController::class, 'store']);
    // Route::get('/mydisputes/{id}', [MyDisputeController::class, 'show']);
    Route::post('/student/disputes/fetchAllDisputes', [MyDisputeController::class, 'fetchAllDisputes']);
    Route::put('/mydisputes/{id}', [MyDisputeController::class, 'update']);
    Route::delete('/mydisputes/{id}', [MyDisputeController::class, 'destroy']);
});

// Route::group(['middleware' => 'auth:teacher-api'], function () {
//     Route::get('/teacher-profile', [TeacherAuthController::class, 'profile']);
// });


Route::get('/home/loadReviews',[ReviewController::class, 'loadHomeReviews']);
Route::get("/general/books/fetchAllBooks",[PublicBooksController::class, "fetchAllBooks"]);

/////////////////***************Teacher Routes **************//////////////////

/////////////////***************Teacher AUTH **************//////////////////

Route::post("/teacher/auth/teacherLogin" , [TeacherAuthController::class,'teacherLogin']);
// NOTE: ye "registerteacher" method TeacherAuthController mein exist nahi karta abhi - is route ko hit karne pe error aayega
Route::post("/Teacher/auth/registerteacher", [TeacherAuthController::class, "registerteacher"]);
Route::post('/teacher/profile/updateProfile',[TeacherAuthController::class, 'updateProfile']);
Route::post('/teacher/password/changePassword',[TeacherAuthController::class, 'changePassword']);
Route::middleware(['auth:Lbteacher', 'guard:teacher'])->group(function () {

    /////////////////***************Teacher REVIEWS **************//////////////////
    Route::post('/teacher/reviews/submitReview', [ReviewController::class, 'submitReview']);
    Route::post('/teacher/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);
    Route::post('/teacher/reviews/submitReview',[ReviewController::class,'submitReview'] );

    ////////////////*****************Teacher notes******************////////////////////
    Route::post('/teacher/notes/uploadNote',          [TeacherNotesController::class, 'uploadNote']);
    Route::get('/teacher/notes/loadAllNotes',         [TeacherNotesController::class, 'loadAllNotes']);
    Route::get('/teacher/notes/loadAllNotes/{teacher_id}',[TeacherNotesController::class,'loadAllNotes']);
    Route::delete('/teacher/notes/deleteNote/{id}',   [TeacherNotesController::class, 'deleteNote']);
    Route::post('/teacher/notes/updateNote/{id}',     [TeacherNotesController::class, 'updateNote']);
});

Route::get('/notes/getAllPublicNotes', [TeacherNotesController::class, 'loadAllPublicNotes']);

Route::middleware(['auth:Lbteacher', 'guard:teacher'])->group(function () {

    ////////////////////****************** Teacher Disputes***************/////////////////
    Route::post('/teacher/disputes/fetchAllDisputes', [StaffDisputeController::class, 'fetchAllDisputes']);
    Route::post('/teacher/disputes/add', [StaffDisputeController::class, 'add']);
    Route::post('/teacher/disputes/update', [StaffDisputeController::class, 'update']);
    Route::post('/teacher/disputes/delete', [StaffDisputeController::class, 'delete']);

    ///////////////////*****************Teacher Booking ****************//////////////////
    Route::post("/teacher/booking/newReservation", [TeacherBookingController::class, "newReservation"]);
    Route::post("/teacher/booking/loadMyBookings", [TeacherBookingController::class, "loadMyBookings"]);

    ////////////////////**************Teacher Dispute Chat Routes *****************////////////////////
    // Route::get('/teacher/disputes/{dispute}/chats',  [TeacherChatController::class, 'index']);
    Route::post('/teacher/chat/store', [TeacherChatController::class, 'store']);
    Route::post('/teacher/chat/fetchAllChats', [TeacherChatController::class, 'fetchAllChats']);

    /////////////////****************Teacher Notification *************/////////////////////
    Route::post('/teacher/notifications/fetchAllNotifications',[TeacherNotificationController::class,'fetchAllNotifications'] );
    Route::post('/teacher/notifications/markAllAsRead',[TeacherNotificationController::class,'markAllAsRead'] );
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/////////////////***************Admin Routes **************//////////////////

////////////////****************Admin Auth ****************//////////////////
Route::post("/admin/auth/adminRegister", [AdminAuthController::class, "adminRegister"]);
Route::post("/admin/auth/adminLogin",    [AdminAuthController::class, "adminLogin"]);


Route::middleware(['auth:Lbadmin', 'guard:admin'])->group(function () {

    // NOTE: ye pehle bina protection ke public tha - subadmin list expose ho rahi thi, ab protect kar diya
    Route::get("/admin/subadmin/list", [AdminAuthController::class, "loadAllSubAdmins"]);

    ////////////////**************Admin Review *****************///////////////////
    Route::post('/admin/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);
    Route::post('/admin/reviews/approve',        [ReviewController::class, 'approveReview']);
    Route::post('/admin/reviews/reject',         [ReviewController::class, 'rejectReview']);
    Route::post('/admin/reviews/delete',         [ReviewController::class, 'deleteReview']);

    //////////////****************Admin manage User *************/////////////////////
    Route::post("/admin/teacherAuth/registerTeacher", [AdminUserController::class, "registerTeacher"]);
    Route::get("/admin/teacherAuth/loadAllTeacher", [AdminUserController::class, "loadAllTeacher"]);
    Route::get("/admin/studentAuth/loadAllStudents",[AdminUserController::class,"loadAllStudents"]);

    /////////////****************Admin manage Books **************//////////////////
    Route::prefix('/admin/books')->group(function () {
        Route::get('/fetchAllBooks', [AdminBookController::class, 'fetchAllBooks']);
        Route::post('/addBook', [AdminBookController::class, 'addBook']);
        Route::post('/update/{id}', [AdminBookController::class, 'updateBook']);
        Route::delete('/delete/{id}', [AdminBookController::class, 'deleteBook']);
    });

    Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);

    Route::get("/admin/bookings/fetchAllBookings", [AdminBookingsController::class, "fetchAllBookings"]);

    //////////////////**************Admin manage Booking *************//////////////////
    Route::post("/admin/bookings/approveReservation", [AdminBookingsController::class, "approveReservation"]);
    Route::post("/admin/bookings/rejectReservation",  [AdminBookingsController::class, "rejectReservation"]);
    Route::post("/admin/bookings/returnBook", [AdminBookingsController::class, "returnBook"]);

    ///////////////////*************ADMIN NOTIFICATIONS**************/////////////////////
    Route::post("/admin/notification/fetchAllNotifications", [AdminNotificationController::class, "fetchAllNotifications"]);
    Route::post("/admin/notification/markAllAsRead", [AdminNotificationController::class, "markAllAsRead"]);

    /////////////////***************Admin manage Blogs ***************//////////////////
    Route::prefix('admin/blogs')->group(function () {
        Route::get('/fetchAllBlogs', [AdminBlogsController::class, 'fetchAllBlogs']);
        Route::post('/addBlog',      [AdminBlogsController::class, 'addBlog']);
        Route::post('/update/{id}',  [AdminBlogsController::class, 'updateBlog']);
        Route::delete('/delete/{id}',[AdminBlogsController::class, 'deleteBlog']);
    });

    ///////////////*************Admin Chat Routes****************///////////////
    Route::post('/admin/chat/fetchAllChats', [AdminChatController::class, 'fetchAllChats']);
    Route::post('/admin/chat/store', [AdminChatController::class, 'store']);

    /////////////***************Admin manage eBooks *************//////////////
    Route::prefix('admin/ebooks')->group(function () {
        Route::post('upload',        [AdminDigiBooksController::class, 'uploadEbook']);
        Route::get('all',            [AdminDigiBooksController::class, 'loadAllEbooks']);
        Route::post('update/{id}',   [AdminDigiBooksController::class, 'updateEbook']);
        Route::delete('delete/{id}', [AdminDigiBooksController::class, 'deleteEbook']);
    });

    ///////////////************* Admin Disputes***************///////////////////
    Route::get('/admin/disputes/fetchAllDisputes', [AdminDisputeController::class, 'fetchAllDisputes']);
    Route::post('/admin/disputes/resolve', [AdminDisputeController::class, 'resolve']);
    Route::post('/admin/disputes/delete', [AdminDisputeController::class, 'delete']);
});
