<?php

// use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\admin\auth\AdminAuthController;
// use App\Http\Controllers\admin\bookings\AdminBookingsController;
use App\Http\Controllers\Admin\blogs\AdminBlogsController;
use App\Http\Controllers\admin\bookings\AdminBookingsController;
use App\Http\Controllers\Admin\bookings\AdminDigiBooksController;
use App\Http\Controllers\admin\notificaion\AdminNotificationController;
use App\Http\Controllers\general\books\PublicBooksController;
use App\Http\Controllers\student\auth\StudentAuthController;
use App\Http\Controllers\student\booking\StudentBookingController;
use App\Http\Controllers\student\notification\StudentNotificationController;
use App\Http\Controllers\Teacher\auth\TeacherAuthController;


use App\Http\Controllers\Teacher\Booking\TeacherBookingController;
use App\Http\Controllers\Teacher\notes\TeacherNotesController;
use App\Http\Controllers\general\mydispute\StaffDisputeController;

use App\Http\Controllers\general\mydispute\MyDisputeController;
use App\Http\Controllers\general\mydispute\AdminDisputeController;



use App\Http\Controllers\Teacher\reviewController\ReviewController;
// use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will`   
| be assigned to the "api" middleware group. Make something great!
|
*/
/////////////////***************Student Routes **************//////////////////

Route::post("/student/auth/studentRegister", [StudentAuthController::class, "studentRegister"]);
Route::post("/student/auth/studentLogin", [StudentAuthController::class, "studentLogin"]);


Route::post("/student/booking/newReservation", [StudentBookingController::class, "newReservation"]);
Route::post("/student/booking/loadMyBookings",  [StudentBookingController::class, "loadMyBookings"]); // ← ADD
Route::post("/student/booking/fetchAllBooks", [StudentBookingController::class, "fetchAllBooks"]);
Route::post("/student/notification/fetchAllNotifications", [StudentNotificationController::class, "fetchAllNotifications"]);
Route::post("/student/notification/markAllAsRead", [StudentNotificationController::class, "markAllAsRead"]);


// Route::group(['middleware' => 'auth:teacher-api'], function () {
//     Route::get('/teacher-profile', [TeacherAuthController::class, 'profile']);
// });


Route::get("/general/books/fetchAllBooks",[PublicBooksController::class, "fetchAllBooks"]);

Route::post("/admin/auth/adminLogin", [AdminAuthController::class, "adminLogin"]);


Route::post("/Teacher/auth/registerteacher", [TeacherAuthController::class, "registerteacher"]);

/////////////////***************Teacher Routes **************//////////////////



Route::post('/teacher/reviews/submitReview', [ReviewController::class, 'submitReview']);
Route::post('/admin/reviews/approve',        [ReviewController::class, 'approveReview']);
Route::post('/admin/reviews/reject',         [ReviewController::class, 'rejectReview']);
Route::post('/admin/reviews/delete',         [ReviewController::class, 'deleteReview']);
Route::post('/teacher/reviews/loadAllReviews', [ReviewController::class, 'loadAllReviews']);

Route::post("/teacher/auth/teacherLogin" , [TeacherAuthController::class,'teacherLogin']);

Route::get('/notes/getAllPublicNotes', [TeacherNotesController::class, 'loadAllPublicNotes']);

use App\Http\Controllers\student\notes\StudentSavedNotesController;

Route::post('/student/notes/saveNote',                    [StudentSavedNotesController::class, 'saveNote']);
Route::get('/student/notes/getSavedNotes/{student_id}',   [StudentSavedNotesController::class, 'getSavedNotes']);
Route::delete('/student/notes/removeSavedNote/{id}',      [StudentSavedNotesController::class, 'removeSavedNote']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/////////////////***************Admin Routes **************//////////////////

Route::post("/admin/auth/adminRegister", [AdminAuthController::class, "adminRegister"]);
Route::post("/admin/auth/adminLogin",    [AdminAuthController::class, "adminLogin"]);
Route::get("/admin/subadmin/list", [AdminAuthController::class, "loadAllSubAdmins"]);

Route::post("/admin/teacherAuth/registerTeacher", [AdminUserController::class, "registerTeacher"]);
Route::get("/admin/teacherAuth/loadAllTeacher", [AdminUserController::class, "loadAllTeacher"]);
Route::get("/admin/studentAuth/loadAllStudents",[AdminUserController::class,"loadAllStudents"]);

Route::prefix('/admin/books')->group(function () {
    Route::get('/fetchAllBooks', [AdminBookController::class, 'fetchAllBooks']);
    Route::post('/addBook', [AdminBookController::class, 'addBook']);  // 'add' se 'addBook' karo
    Route::post('/update/{id}', [AdminBookController::class, 'updateBook']);
    Route::delete('/delete/{id}', [AdminBookController::class, 'deleteBook']);
});
Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);
Route::post("/admin/bookings/fetchAllBookings", [AdminBookingsController::class, "fetchAllBookings"]);
// Route::get("/admin/bookings/fetchAllBookings", [AdminBookingsController::class, "fetchAllBookings"]);

 
Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);

Route::get("/admin/bookings/fetchAllBookings", [AdminBookingsController::class, "fetchAllBookings"]);

Route::post("/admin/bookings/approveReservation", [AdminBookingsController::class, "approveReservation"]);
Route::post("/admin/bookings/rejectReservation",  [AdminBookingsController::class, "rejectReservation"]);
Route::post("/admin/bookings/returnBook", [AdminBookingsController::class, "returnBook"]);

// ********************************ADMIN NOTIFICATIONS***********************************
Route::post("/admin/notification/fetchAllNotifications", [AdminNotificationController::class, "fetchAllNotifications"]);
Route::post("/admin/notification/markAllAsRead", [AdminNotificationController::class, "markAllAsRead"]);


Route::prefix('admin/blogs')->group(function () {
    Route::get('/fetchAllBlogs', [AdminBlogsController::class, 'fetchAllBlogs']);
    Route::post('/addBlog',      [AdminBlogsController::class, 'addBlog']);
    Route::post('/update/{id}',  [AdminBlogsController::class, 'updateBlog']);
    Route::delete('/delete/{id}',[AdminBlogsController::class, 'deleteBlog']);
});

 

//////////////////Crud of notes //////////////////////////////////

Route::post('/teacher/notes/uploadNote',          [TeacherNotesController::class, 'uploadNote']);
Route::get('/teacher/notes/loadAllNotes',         [TeacherNotesController::class, 'loadAllNotes']);
Route::get('/teacher/notes/loadAllNotes/{teacher_id}',[TeacherNotesController::class,'loadAllNotes']);
Route::delete('/teacher/notes/deleteNote/{id}',   [TeacherNotesController::class, 'deleteNote']);
Route::post('/teacher/notes/updateNote/{id}',     [TeacherNotesController::class, 'updateNote']);

Route::prefix('admin/ebooks')->group(function () {
    Route::post('upload',        [AdminDigiBooksController::class, 'uploadEbook']);
    Route::get('all',            [AdminDigiBooksController::class, 'loadAllEbooks']);
    Route::post('update/{id}',   [AdminDigiBooksController::class, 'updateEbook']);
    Route::delete('delete/{id}', [AdminDigiBooksController::class, 'deleteEbook']);
});

// Student Disputes
Route::get('/mydisputes', [MyDisputeController::class, 'index']);
Route::post('/mydisputes', [MyDisputeController::class, 'store']);
Route::get('/mydisputes/{id}', [MyDisputeController::class, 'show']);
Route::put('/mydisputes/{id}', [MyDisputeController::class, 'update']);
Route::delete('/mydisputes/{id}', [MyDisputeController::class, 'destroy']);


// Staff Disputes
Route::get('/staffdisputes', [StaffDisputeController::class, 'index']);
Route::post('/staffdisputes', [StaffDisputeController::class, 'store']);
Route::post('/teacher/disputes/viewAllDisputes', [StaffDisputeController::class, 'viewAllDisputes']);
Route::get('/staffdisputes/{id}', [StaffDisputeController::class, 'show']);
Route::put('/staffdisputes/{id}', [StaffDisputeController::class, 'update']);
Route::delete('/staffdisputes/{id}', [StaffDisputeController::class, 'destroy']);
Route::post("/teacher/booking/newReservation", [TeacherBookingController::class, "newReservation"]);
Route::post("/teacher/booking/loadMyBookings", [TeacherBookingController::class, "loadMyBookings"]);




// Admin Disputes
Route::get('/admin/disputes', [AdminDisputeController::class, 'index']);
Route::patch('/admin/disputes/{id}/resolve', [AdminDisputeController::class, 'resolve']);
Route::delete('/admin/disputes/{id}', [AdminDisputeController::class, 'destroy']);


Route::post('/teacher/reviews/submitReview',[ReviewController::class,'submitReview'] );

