<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTeacherController extends Controller
{
    public function index() {
        $teachers = DB::select('SELECT * FROM dbo.teachers');
        return response()->json($teachers);
    }

    public function create()
    {
        return Inertia::render('Teacher/New');
    }

    public function store(TeacherRequest $request) {
        $data = $request->all();
        DB::insert('INSERT INTO dbo.teachers (last_name, first_name, teacher_code, department, faculty, address, phone, note) 
                        VALUES (?,?,?,?,?,?,?,?)', 
                        [$data['last_name'], $data['first_name'], $data['teacher_code'], $data['department'], $data['faculty'], $data['address'], $data['phone'], $data['note']]);
            $hashPwd = Hash::make("Bmvt@hcmut");
            $name = $data['last_name']." ".$data['first_name'];
            $teacher = DB::select('SELECT top(1) * FROM dbo.teachers ORDER BY dbo.teachers.id DESC')[0];
            $teacher_id = $teacher->id;
        DB::insert('INSERT INTO dbo.users (name, email, password, role_id, teacher_id)
                    VALUES (?,?,?,?,?)',
                    [$name, $data['email'], $hashPwd, 3, $teacher_id]);
        return response()->json($teacher);
    }
}
