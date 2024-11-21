<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    public function model(array $row)
    {
        return new Student([
            'name' => $row[0],
            'level' => $row[1],
            'department' => $row[2],
        ]);
    }
}
