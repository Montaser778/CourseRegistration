<?php

namespace App\Imports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\ToModel;

class CoursesImport implements ToModel
{
    public function model(array $row)
    {
        return new Course([
            'name' => $row[0],
            'difficulty' => $row[1],
            'category' => $row[2],
        ]);
    }
}
