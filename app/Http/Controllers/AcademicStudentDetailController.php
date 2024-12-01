<?php

namespace App\Http\Controllers\response\Controller;
namespace App\Http\Controllers;
namespace App\Http\Controllers\response;

use Illuminate\Http\Request;
use App\Models\AcademicStudentDetail;
use App\Http\Controllers\response\Controller;
class AcademicStudentDetailController extends Controller
{
    public function index()
    {
        $students = AcademicStudentDetail::all();
        return response()->json($students);
    }
}
