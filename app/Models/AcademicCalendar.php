<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AcademicCalendar extends Model
{
    use HasFactory;

    // تأكد من أن الجدول مهيأ بشكل صحيح
    protected $table = 'academic_calendars';
}
