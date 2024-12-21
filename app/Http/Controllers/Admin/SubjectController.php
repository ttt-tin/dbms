<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = DB::select('SELECT * FROM dbo.subjects');
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return Inertia::render('Subject/New');
    }

    public function store(SubjectRequest $request)
    {
        DB::insert('INSERT INTO dbo.subjects (name, code, note) VALUES (?,?,?)', [$request->name, $request->code, $request->note]);
        $subject = DB::select('SELECT top(1) * FROM dbo.subjects ORDER BY dbo.subjects.id DESC')[0];
        return response()->json($subject);
    }

}
