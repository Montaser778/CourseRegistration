<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $table = 'academiccalendar'; // تحديد اسم الجدول

    // أعمدة معينة تحتاج لحمايتها من الكتابة
    protected $guarded = [];
}
