<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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


Route::middleware(['auth'])->get('/recommendations', [RecommendationController::class, 'getRecommendationsForCurrentUser'])
     ->name('recommendations');

Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations');
Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
require __DIR__.'/auth.php';
