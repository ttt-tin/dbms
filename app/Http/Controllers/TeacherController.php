<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->q;
        $teacherId = Auth::user()->teacher_id;
        $result = DB::select("
            SELECT dbo.reports.id, last_name, first_name, name, code, year, semester, title, path
            FROM dbo.reports
            INNER JOIN dbo.teacher_to_subjects
            ON dbo.teacher_to_subjects.id = teacher_to_subjects_id
            INNER JOIN dbo.students
            ON dbo.students.id = reports.student_id
            INNER JOIN dbo.subjects
            ON dbo.subjects.id = dbo.teacher_to_subjects.subject_id
            WHERE teacher_id = ".$teacherId."
            AND last_name LIKE '%".$q."%' 
            OR first_name LIKE '%".$q."%' 
            OR name LIKE '%".$q."%' 
            OR code LIKE '%".$q."%' 
        ");
        return response()->json($result);
    }

    public function setMark(MarkReportRequest $request) 
    {
        $id = $request->report_id;
        DB::update('UPDATE dbo.reports SET mark = ? WHERE id = ?', [$request->mark, $id]);

        return response()->json('cập nhật điểm thành công');
    }
}
