<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCalendar;

class AcademicCalendarController extends Controller
{
    public function index()
    {
        $calendar = AcademicCalendar::all();
        return response()->json($calendar);
        // أو يمكن استخدام السطر التالي بدلاً منه إذا استمرت المشكلة
        // return \Illuminate\Support\Facades\Response::json($calendar);
    }
    public function testModel()
{
    $calendar = AcademicCalendar::first(); // أو AcademicCalendar::all();
    return response()->json($calendar);
    }
}
