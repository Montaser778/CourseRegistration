<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataExportController extends Controller
{
    public function exportToCsv()
    {
        $students = DB::table('academicstudentdetails')
            ->join('academiccalendar', 'academicstudentdetails.student_id', '=', 'academiccalendar.student_id')
            ->select('academicstudentdetails.grades', 'academicstudentdetails.past_courses_count', 'academiccalendar.course_difficulty')
            ->get();

        $fileName = 'student_course_data.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = ['grades', 'past_courses_count', 'course_difficulty'];

        $callback = function() use ($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->grades,
                    $student->past_courses_count,
                    $student->course_difficulty
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

