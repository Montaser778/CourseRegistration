<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Course;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Http;

abstract class Controller
{

    public function getRecommendations($studentId)
{
    $response = Http::post('http://127.0.0.1:5000/recommend', [
        'student_id' => $studentId
    ]);

    $recommendations = $response->json();
    return view('recommendations', compact('recommendations'));
}

public function importCourses(Request $request)
{
    Excel::import(new CoursesImport, $request->file('file'));

        return redirect()->back()->with('success', 'Courses imported successfully.');

    return redirect()->back()->with('success', 'Courses imported successfully.');
}

public function getRecommendation($student_id, $difficulty, $category)
{
    $process = new Process(['python3', 'recommend_course.py', $student_id, $difficulty, $category]);
    $process->run();

    if (!$process->isSuccessful()) {
        throw new ProcessFailedException($process);
    }

    $recommendation = $process->getOutput();
    return response()->json(['recommendation' => $recommendation]);
}

    //
}
