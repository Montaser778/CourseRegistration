<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/courses', [CourseController::class, 'getCourses']);
Route::get('/recommendations', [CourseController::class, 'getRecommendations']);
Route::post('/register', [CourseController::class, 'registerCourses']);
