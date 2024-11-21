<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    <?php

public function importStudents(Request $request)
{
    Excel::import(new StudentsImport, $request->file('file'));
    return redirect()->back()->with('success', 'Students imported successfully.');
}

public function exportDataForTraining()
{
    $data = DB::table('registrations')
        ->join('students', 'registrations.student_id', '=', 'students.id')
        ->join('courses', 'registrations.course_id', '=', 'courses.id')
        ->select('students.id as student_id', 'courses.id as course_id', 'registrations.grade', 'courses.difficulty', 'courses.category')
        ->get();

    $filePath = storage_path('app/public/training_data.csv');
    $file = fopen($filePath, 'w');
    fputcsv($file, ['student_id', 'course_id', 'grade', 'difficulty', 'category']);

    foreach ($data as $row) {
        fputcsv($file, [(string) $row->student_id, (string) $row->course_id, (string) $row->grade, (string) $row->difficulty, (string) $row->category]);
    }

    fclose($file);

    return response()->download($filePath);
    }
}
