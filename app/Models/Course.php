<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function similarCourses()
{
    return self::where('category', $this->category)
        ->where('id', '!=', $this->id) // لا تشمل نفس الدورة
        ->get();
    }

    protected $fillable = [
        'name',
        'category',
        'description',
        'is_active', // الحقل الجديد
    ];

}
