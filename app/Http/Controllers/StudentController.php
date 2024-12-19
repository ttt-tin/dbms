<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function create()
    {
        return Inertia::render('Student/New');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {   
        $data = $request->all();
        DB::insert('INSERT INTO dbo.students (last_name, first_name, student_code, department, faculty, address, phone, note) 
                        VALUES (?,?,?,?,?,?,?,?)', 
                        [$data['last_name'], $data['first_name'], $data['student_code'], $data['department'], $data['faculty'], $data['address'], $data['phone'], $data['note']]);

                        
        $student = DB::select('SELECT top(1) * FROM dbo.students ORDER BY dbo.students.id DESC')[0];
        DB::update('UPDATE dbo.users SET student_id = '.$student->id.' WHERE id = ?', [$request->user()->id]);  

        return response()->json($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = DB::select('SELECT top(1) * FROM dbo.students WHERE id = ?', [$id])[0];

        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = DB::select('SELECT top(1) * FROM dbo.students WHERE id = ?', [$id])[0];
        
        return $student != null 
        ?   Inertia::render('Student/Update', [
                'student' => $student
            ])
        : abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        DB::insert('UPDATE dbo.students 
                    SET last_name = ?, first_name = ?, student_code = ?, department = ?, faculty = ?, address = ?, phone = ?, note = ? 
                    WHERE id = ?', 
                    [$request->last_name, $request->first_name, $request->department, $request->faculty, $request->address, $request->phone, $request->student_code, $request->note], $id); 
        $student = DB::select('SELECT * FROM dbo.students WHERE id = ?', [$id]);
        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::delete('DELETE FROM dbo.students WHERE id = ?', [$id]);

        return response('message', 'Đã xóa thành công');
    }
}
