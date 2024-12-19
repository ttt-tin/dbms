<?php

use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\TeacherToSubjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/img', [ProfileController::class, 'uploadImg'])->name('profile.update_img');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['middleware' => 'student_check'], function () {
        Route::get('student/create', [StudentController::class, 'create'])->name('student.create');
        Route::middleware('student_null')->group(function () {
            Route::resource('student', StudentController::class)->except(['index', 'create']);
        });
    });

    // admin
    Route::group(['prefix' => 'admin','middleware' => 'admin_check'], function() {
        // Route::get('/dashboard', function () {
        //     return Inertia::render('DashboardAdmin');
        // });
        Route::get('teacher', [AdminTeacherController::class, 'index'])->name('teacher.index');
        Route::get('teacher/new', [AdminTeacherController::class, 'create'])->name('teacher.create');
        Route::post('teacher', [AdminTeacherController::class, 'store'])->name('teacher.store');

        Route::get('subject', [AdminSubjectController::class, 'index'])->name('subject.index');
        Route::get('subject/new', [AdminSubjectController::class, 'create'])->name('subject.create');
        Route::post('subject', [AdminSubjectController::class, 'store'])->name('subject.store');
        
        Route::get('import.teacher', [ImportController::class, 'importTeacher'])->name('import_teacher.create');
        Route::post('import.teacher', [ImportController::class, 'storeTeacher'])->name('import_teacher.store');
        Route::get('import.student', [ImportController::class, 'importStudent'])->name('import_student.create');
        Route::post('import.student', [ImportController::class, 'storeStudent'])->name('import_student.store');
        Route::get('import.subject', [ImportController::class, 'importSubject'])->name('import_subject.create');
        Route::post('import.subject', [ImportController::class, 'storeSubject'])->name('import_subject.store');
            
        Route::get('teacherToSubjects', [TeacherToSubjectController::class, 'index']);
        Route::get('teacherToSubjects/new', [TeacherToSubjectController::class, 'create'])->name('teacherToSubjects.create');
        Route::post('teacherToSubjects', [TeacherToSubjectController::class, 'store'])->name('teacherToSubjects.store');

        Route::get('export', [AdminExportController::class, 'create'])->name('export.mark');
        Route::get('/export.mark', [AdminExportController::class, 'exportMark']);
    });

    //student
    Route::group(['prefix' => 'student'], function() {
        Route::post('/api/search', [ReportController::class, 'search']);
        Route::post('/upload.report', [ReportController::class, 'store'])->name('upload_report');
    });

    //teacher
    Route::group(['prefix' => 'teacher'], function() {
        Route::post('/api/search', [TeacherController::class, 'search']);
        Route::post('/setmark', [TeacherController::class, 'setMark'])->name('teacher.setmark');
    });

    Route::get('api/view.report', [ReportController::class, 'viewReport'])->name('view.report');
    Route::get('api/download.report', [ReportController::class, 'downloadReport'])->name('download.report');
});

require __DIR__.'/auth.php';
