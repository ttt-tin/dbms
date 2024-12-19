<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeachersImport implements ToCollection, WithStartRow
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
            DB::insert('INSERT INTO dbo.teachers (last_name, first_name, teacher_code, department, faculty, address, phone, note) 
                        VALUES (?,?,?,?,?,?,?,?)', 
                        [$row[0], $row[1], $row[3], $row[5], $row[4], $row[6], $row[7], $row[8]]);
            $name = $row[0]." ".$row[1];
            $hashPwd = Hash::make("Bmvt@hcmut");
            $teacher_id = DB::select('SELECT top(1) id FROM dbo.teachers ORDER BY dbo.teachers.id DESC')[0]->id;
            DB::insert('INSERT INTO dbo.users (name, email, password, role_id, teacher_id)
                        VALUES (?,?,?,?,?)',
                        [$name, $row[2], $hashPwd, 3, $teacher_id]);
        }
    }
}
