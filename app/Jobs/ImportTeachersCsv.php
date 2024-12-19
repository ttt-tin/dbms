<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeachersImport;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportTeachersCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $teacherImport;

    /**
     * Create a new job instance.
     */
    public function __construct($path, $teacherImport)
    {
        $this->path = $path;
        $this->teacherImport = $teacherImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::update('UPDATE dbo.imports SET status = 1 WHERE id = ?', [$this->teacherImport->id]);
        // chmod($this->path, 0666);

        Excel::import(new TeachersImport, $this->path);

        DB::update('UPDATE dbo.imports SET status = 2 WHERE id = ?', [$this->teacherImport->id]);
    }

    public function fail(Throwable $exception)
    {
        DB::update('UPDATE dbo.imports SET status = 3 WHERE id = ?', [$this->teacherImport->id]);
    }
}
