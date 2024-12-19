<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;


class StudentsImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row ) {
            DB::insert('INSERT INTO dbo.students (last_name, first_name, student_code, department, faculty, address, phone, note) 
                        VALUES (?,?,?,?,?,?,?,?)', 
                        [$row[0], $row[1], $row[3], $row[5], $row[4], $row[6], $row[7], $row[8]]);
            $name = $row[0]." ".$row[1];
            $student_id = DB::select('SELECT top(1) id FROM dbo.students ORDER BY dbo.students.id DESC')[0]->id;
            DB::insert('INSERT INTO dbo.users (name, email, password, role_id, student_id)
                        VALUES (?,?,?,?,?)',
                        [$name, $row[2], Hash::make($row[9]), 4, $student_id]);
        }
    }
}
