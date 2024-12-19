<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function store(Request $request) 
    {
        // dd($request->all());
        $teacherToSubject = DB::select('SELECT * FROM dbo.teacher_to_subjects where id = ?', [$request->teacher_to_subjects_id])[0];
        $year = $teacherToSubject->year;
        $semester = $teacherToSubject->semester;
        $file_name = date('Ymd_His_').$request->file->getClientOriginalName();
        $file_path = storage_path('app\\data\\reports\\'.$year.'\\'.$semester.'\\'.$teacherToSubject->id.'\\'.$file_name);

        DB::insert("INSERT INTO dbo.reports (student_id, teacher_to_subjects_id, title, path) VALUES(?,?,?,?)", [Auth::user()->student_id, $request->teacher_to_subjects_id, $request->title, $file_path]);

        $request->file->move(storage_path('app\\data\\reports\\'.$year.'\\'.$semester.'\\'.$teacherToSubject->id), $file_name);

        return response()->json('Upload file thành công');
    }

    public function search(Request $request) 
    {
        $q = $request->q;
        $result = DB::select("
                        SELECT dbo.teacher_to_subjects.id, last_name, first_name, name, code, year, semester 
                        FROM dbo.teacher_to_subjects 
                        INNER JOIN dbo.teachers 
                        ON dbo.teachers.id = dbo.teacher_to_subjects.teacher_id
                        INNER JOIN dbo.subjects
                        ON dbo.subjects.id = dbo.teacher_to_subjects.subject_id
                        WHERE last_name LIKE '%".$q."%' 
                        OR first_name LIKE '%".$q."%' 
                        OR name LIKE '%".$q."%' 
                        OR code LIKE '%".$q."%' 
                    ");
        return response()->json($result);
    }

    public function viewReport(Request $request) 
    {
        $pathToFile = $request->path;
        return response()->file($pathToFile);
    }

    public function downloadReport(Request $request) 
    {
        $pathToFile = $request->path;
        return response()->download($pathToFile);
    }
}
