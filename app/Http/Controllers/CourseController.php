<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CoursesImport;
use Symfony\Component\Process\Process;
use App\Models\Course;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function enroll($courseId)
    {
        $user = auth()->user();
        $user->recommendations()->create(['course_id' => $courseId]);

        return redirect()->back()->with('success', 'Course enrolled successfully!');
    }

    public function getrecommendedCourses()
    {
        // هنا لجلب التوصيات من قاعدة البيانات
        $recommendations = [1, 3]; // : قائمة بمعرّفات الدورات الموصى بها
        return response()->json($recommendations);
    }

    public function registerCourses(Request $request)
    {
        // هنا لمعالجة تسجيل الطالب في الدورات المحددة
        $courseIds = $request->input('courses'); // قائمة معرّفات الدورات المحددة
        //  لحفظ التسجيلات في قاعدة البيانات هنا
        return response()->json(['message' => 'Registration successful']);
    }

    public function importCourses(Request $request)
{
    $path = $request->file('file')->getRealPath();
    $data = Excel::load($path, function($reader) {})->get();

    if (!empty($data) && $data->count()) {
        foreach ($data as $row) {
            Course::create([
                'name' => $row->name,
                'difficulty' => $row->difficulty,
                'category' => $row->category,
            ]);
        }
    }

    return redirect()->back()->with('success', 'Courses imported successfully.');
}
    public function importMultipleCourses(Request $request)
{
    // التحقق من وجود الملفات
    if ($request->hasFile('files')) {
        // تكرار الملفات ومعالجتها واحدة تلو الأخرى
        foreach ($request->file('files') as $file) {
            Excel::import(new CoursesImport, $file); // استيراد البيانات من الملف
        }

        return redirect()->back()->with('success', 'All files imported successfully.');
    }

    return redirect()->back()->with('error', 'Please upload at least one file.');
}

    public function getRecommendation($student_id, $difficulty, $category)
{
    // تأكد من أنك تستخدم المسار الصحيح للسكربت
    $process = new Process(['python3', '../ml_training/recommend_course.py', $student_id, $difficulty, $category]);
    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $recommendation = trim($process->getOutput());
    return response()->json(['recommendation' => $recommendation]);
    }

    public function show($id)
{
    $course = Course::findOrFail($id);

    return view('courses.show', compact('course'));
    }
}
