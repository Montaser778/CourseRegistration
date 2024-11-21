<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicStudentDetailController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\DataExportController;

Route::post('/recommend', [RecommendationController::class, 'recommend']);
Route::get('/export', [DataExportController::class, 'exportToCsv']);Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/academiccalendar', [AcademicCalendarController::class, 'index']);
Route::get('/academicstudentdetails', [AcademicStudentDetailController::class, 'index']);
Route::get('/register', [CourseController::class, 'showRegisterForm'])->name('course.registerForm');
Route::post('/register', [CourseController::class, 'register'])->name('course.register');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/register-courses', function () {
    return view('courses.registration');
});

Route::post('/import-courses', [CourseController::class, 'importCourses'])->name('courses.import');

// مسار GET لعرض صفحة التحميل
Route::get('/import-courses', function () {
    return view('courses.import');  // عرض صفحة تحميل الملف
})->name('courses.import.view');

// مسار POST لمعالجة رفع الملف واستيراده
Route::post('/import-courses', [CourseController::class, 'importCourses'])->name('courses.import');

Route::get('/import-courses', function () {
    return view('courses.import');
})->name('courses.import.view');

Route::post('/import-multiple-courses', [CourseController::class, 'importMultipleCourses'])->name('courses.import.multiple');

Route::get('/recommendation', [RecommendationController::class, 'show'])->name('recommend.show');
Route::post('/recommendation', [RecommendationController::class, 'getRecommendation'])->name('recommend.course');

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// عرض جميع الدورات
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

// تسجيل المستخدم في دورة
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

// عرض توصيات المستخدم
Route::get('/user/recommendations', [RecommendationController::class, 'userRecommendations'])->name('user.recommendations');

// توصية قائمة بناءً على معرف المستخدم
Route::get('/recommend/{userId}', [RecommendationController::class, 'recommend'])->name('recommend.user');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::get('/recommendations/{userId}', [RecommendationController::class, 'getRecommendations'])->name('recommendations');

Route::get('/recommendations/{userId}', [RecommendationController::class, 'getRecommendationsFromPython'])->name('recommendations.python');
