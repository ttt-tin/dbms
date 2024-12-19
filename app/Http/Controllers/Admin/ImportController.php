<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportFileCsvRequest;
use App\Jobs\ImportStudentsCsv;
use App\Jobs\ImportSubjectsCsv;
use App\Jobs\ImportTeachersCsv;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Import;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function importTeacher() {
        return Inertia::render('Admin/ImportTeacher');
    }

    public function storeTeacher(ImportFileCsvRequest $request) {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\'.$file_name);

        DB::insert('INSERT INTO dbo.imports (name, path, status, created_by) 
                    VALUES (?,?,?,?)',
                    [$file_name, $file_path, 0, Auth::user()->name]);
        $import = DB::select('SELECT top(1) * FROM dbo.imports ORDER BY dbo.imports.id DESC')[0];
        $request->csv_import->move(storage_path('app\\data\\'), $file_name);

        $teacherImport = Import::latest()->first();
        $teacherImport = $import;
        $path = $file_path;

        ImportTeachersCsv::dispatch($path, $teacherImport)->delay(10);
        return response()->json('Tải file thành côngs');
    }

    public function importStudent() {
        return Inertia::render('Admin/ImportStudent');
    }

    public function storeStudent(ImportFileCsvRequest $request) {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\'.$file_name);

        DB::insert('INSERT INTO dbo.imports (name, path, status, created_by) 
                    VALUES (?,?,?,?)',
                    [$file_name, $file_path, 0, Auth::user()->name]);
        $import = DB::select('SELECT top(1) * FROM dbo.imports ORDER BY dbo.imports.id DESC')[0];
        
        $request->csv_import->move(storage_path('app\\data\\'), $file_name);

        $studentImport = $import;
        $path = $file_path;

        ImportStudentsCsv::dispatch($path, $studentImport)->delay(10);
        return response()->json('Tải file thành công, đang chờ xử lý');
    }

    public function importSubject() {
        return Inertia::render('Admin/ImportSubject');
    }

    public function storeSubject(ImportFileCsvRequest $request) {
        $file_name = date('Ymd_His_').$request->csv_import->getClientOriginalName();
        $file_path = storage_path('app\\data\\'.$file_name);

        DB::insert('INSERT INTO dbo.imports (name, path, status, created_by) 
        VALUES (?,?,?,?)',
        [$file_name, $file_path, 0, Auth::user()->name]);
        $import = DB::select('SELECT top(1) * FROM dbo.imports ORDER BY dbo.imports.id DESC')[0];
        
        $request->csv_import->move(storage_path('app\\data\\'), $file_name);

        $subjectImport = $import;
        $path = $file_path;

        ImportSubjectsCsv::dispatch($path, $subjectImport)->delay(10);
        return response()->json('Tải file thành công, đang chờ xử lý');
    }
}
