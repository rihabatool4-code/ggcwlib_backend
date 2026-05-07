<?php

// use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminBookController;
use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\student\auth\StudentAuthController;
use App\Http\Controllers\Teacher\auth\TeacherAuthController;
use App\Http\Controllers\admin\auth\AdminAuthController;
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

// Route::group(['middleware' => 'auth:teacher-api'], function () {
//     Route::get('/teacher-profile', [TeacherAuthController::class, 'profile']);
// });


Route::post("/admin/auth/adminLogin", [AdminAuthController::class, "adminLogin"]);


Route::post("/Teacher/auth/registerteacher", [TeacherAuthController::class, "registerteacher"]);

/////////////////***************Teacher Routes **************//////////////////

Route::post("/staff/login" ,[TeacherAuthController::class, "teacherLogin"]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/////////////////***************Admin Routes **************//////////////////

Route::post("/admin/teacherAuth/registerTeacher", [AdminUserController::class, "registerTeacher"]);
Route::get("/admin/teacherAuth/loadAllTeacher", [AdminUserController::class, "loadAllTeacher"]);

Route::get("/admin/books/fetchAllBooks", [AdminBookController::class, "fetchAllBooks"]);
