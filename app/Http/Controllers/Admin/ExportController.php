<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function create() 
    {
        return Inertia::render('Admin/ExportMark', [
            'teachers' => DB::select('SELECT * FROM dbo.teachers'),
            'subjects' => DB::select('SELECT * FROM dbo.subjects')
        ]);
    }

    public function exportMark(Request $request)
    {
        // dd($request->all());
        return Excel::download(
            new ReportExport($request->year,
                             $request->semester, 
                             $request->teacherId, 
                             $request->subjectId), 
            'mark.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
