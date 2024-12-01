<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    //
public function course()
{
    return $this->belongsTo(Course::class);
    }
}

