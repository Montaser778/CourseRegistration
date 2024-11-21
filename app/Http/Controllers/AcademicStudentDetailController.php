<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicStudentDetail;

class AcademicStudentDetailController extends Controller
{
    public function index()
    {
        $students = AcademicStudentDetail::all();
        return response()->json($students);
    }
}
