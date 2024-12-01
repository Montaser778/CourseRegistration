<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Http;
use App\Models\Course;

class RecommendationController extends Controller
{
    public function recommend($userId)
    {
        $user = User::findOrFail($userId);
        if (!$user) {
            return response()->json(['error' => 'No users found.'], 404);
        }

        // جلب التوصيات للمستخدم
        $recommendations = $user->recommendations->load('course');

        // عرض التوصيات في الواجهة
        return view('user.recommendations', ['recommendations' => $recommendations]);

        $preferredCategories = $user->recommendations()
        ->with('course')
        ->get()
        ->pluck('course')
        ->unique();

         // جلب الدورات المشابهة
    $recommendedCourses = collect();
    foreach ($preferredCourses as $course) {
        $similarCourses = $course->similarCourses();
        $recommendedCourses = $recommendedCourses->merge($similarCourses);

        $userId = auth()->id(); // معرف المستخدم المسجل

        // إرسال طلب إلى Flask API
    $response = Http::post('http://127.0.0.1:5000/recommend', [
        'user_id' => $userId,
        'courses' => Course::where('is_active', 1)->get()->toArray(),
    ]);

    // معالجة النتائج
    if ($response->ok()) {
        $recommendations = $response->json();
        $courseIds = array_column($recommendations, 'course_id');
        $courses = Course::whereIn('id', $courseIds)->get();

        foreach ($courses as $course) {
            $course->predicted_rating = collect($recommendations)
                ->firstWhere('course_id', $course->id)['predicted_rating'];
        }

        return view('recommendations', compact('courses'));
    }

    return response()->json(['error' => 'Unable to fetch recommendations'], 500);

    // التحقق من نجاح الطلب
    if ($response->ok()) {
        $recommendations = $response->json();


        // استرجاع تفاصيل الدورات
        $courseIds = array_column($recommendations, 'course_id');
        $courses = Course::whereIn('id', $courseIds)->get();

        foreach ($courses as $course) {
            $course->predicted_rating = collect($recommendations)->firstWhere('course_id', $course->id)['predicted_rating'];
        }

        return view('recommendations', compact('courses'));
    }

    return response()->json(['error' => 'Unable to fetch recommendations'], 500);

    // إزالة التكرارات
    $recommendedCourses = $recommendedCourses->unique('id');

    return view('recommendations', compact('recommendedCourses'));

        $recommendedCourses = Course::whereNotIn('id', $user->recommendations->pluck('course_id'))
            ->whereIn('category', $preferredCategories)
            ->take(5)
            ->get();

        return view('recommendations', compact('recommendedCourses'));


    public function getRecommendations($userId)
    {
        // مسار ملف JSON
        $path = 'public/recommendations.json';

        // التحقق من وجود الملف
        if (Storage::exists($path)) {
            // قراءة محتوى ملف JSON
            $content = Storage::get($path);
            $recommendations = json_decode($content, true);

            // استرجاع تفاصيل الدورات من قاعدة البيانات
            $courseIds = array_column($recommendations, 'course_id');
            $courses = Course::whereIn('id', $courseIds)->get();

            // إضافة التقييم المتوقع إلى الدورات
            foreach ($courses as $course) {
                $course->predicted_rating = collect($recommendations)->firstWhere('course_id', $course->id)['predicted_rating'];
            }

            // إرجاع صفحة العرض
            return view('recommendations', compact('courses'));
        }

        // في حالة عدم وجود الملف
        return response()->json(['error' => 'Recommendations not found'], 404);
    }

    public function userRecommendations()
    {

    $user = auth()->user();
    $recommendations = $user->recommendations->load('course');

    return view('user.recommendations', ['recommendations' => $recommendations]);

    }

    public function getRecommendationsFromPython($userId)
    {
        // إرسال طلب إلى Flask API
        $response = Http::post('http://127.0.0.1:5000/recommend', [
            'user_id' => $userId,
            'courses' => Course::all()->toArray(), // جميع الدورات المتاحة
        ]);

        // التحقق من نجاح الطلب
        if ($response->ok()) {
            $recommendations = $response->json();

            // استرجاع تفاصيل الدورات
            $courseIds = array_column($recommendations, 'course_id');
            $courses = Course::whereIn('id', $courseIds)->get();

            // إضافة التقييم المتوقع إلى الدورات
            foreach ($courses as $course) {
                $course->predicted_rating = collect($recommendations)
                    ->firstWhere('course_id', $course->id)['predicted_rating'];
            }

            // عرض التوصيات في واجهة Blade
            return view('recommendations', compact('courses'));
        }

        // إذا فشل الطلب
        return response()->json(['error' => 'Unable to fetch recommendations'], 500);
    }

    public function index()
    {
        $userId = auth()->id();
        $response = Http::post('http://127.0.0.1:5000/recommend', [
            'user_id' => $userId,
            'courses' => Course::all()->toArray(),
        ]);
        if ($response->ok()) {
            $recommendations = $response->json();
            $courseIds = array_column($recommendations, 'course_id');
            $courses = Course::whereIn('id', $courseIds)->get();

            return view('recommendations', compact('courses'));
        }

        return redirect()->back()->with('error', 'Failed to fetch recommendations.');
    }
}

