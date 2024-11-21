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
    }
}
