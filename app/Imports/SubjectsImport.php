<?php

namespace App\Imports;

use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubjectsImport implements ToCollection, WithStartRow
{
    /**
    * @param Collection $collection
    */

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row ) {
            DB::insert('INSERT INTO dbo.subjects (name, code, note) VALUES (?,?,?)', [$row[0], $row[1], $row[2]]);
        }
    }
}
