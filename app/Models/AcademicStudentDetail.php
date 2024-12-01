<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicStudentDetail extends Model
{
    use HasFactory;

    protected $table = 'academicstudentdetails'; // تحديد اسم الجدول

    // أعمدة معينة تحتاج لحمايتها من الكتابة
    protected $guarded = [];
}

