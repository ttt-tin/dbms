<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\TeacherToSubjectsRequest;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherToSubject;
use Illuminate\Support\Facades\DB;

class TeacherToSubjectController extends Controller
{
    public function index() 
    {
        
    }

    public function create() 
    {
        $teachers = DB::select('SELECT * FROM dbo.teachers');
        $subjects = DB::select('SELECT * FROM dbo.subjects');
        return Inertia::render('TeacherToSubjects/New', [
            'teachers' => $teachers,
            'subjects' => $subjects
        ]);
    }

    public function store(TeacherToSubjectsRequest $request) 
    {
        DB::insert('INSERT INTO dbo.teacher_to_subjects (teacher_id, subject_id, semester, year) 
                    VALUES (?,?,?,?)',
                    [$request->teacher_id, $request->subject_id, $request->semester, $request->year]);
        $teacherToSubject = DB::select('SELECT top(1) * FROM dbo.teacher_to_subjects ORDER BY dbo.teacher_to_subjects.id DESC')[0];
        return response()->json($teacherToSubject);
    }
}
