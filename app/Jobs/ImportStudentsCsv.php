<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\DB;
use Throwable;

class ImportStudentsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $studentImport;

    /**
     * Create a new job instance.
     */
    public function __construct($path, $studentImport)
    {
        $this->path = $path;
        $this->studentImport = $studentImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::update('UPDATE dbo.imports SET status = 1 WHERE id = ?', [$this->studentImport->id]);
        
        Excel::import(new StudentsImport, $this->path);

        DB::update('UPDATE dbo.imports SET status = 2 WHERE id = ?', [$this->studentImport->id]);
    }

    public function fail(Throwable $exception)
    {
        DB::update('UPDATE dbo.imports SET status = 3 WHERE id = ?', [$this->studentImport->id]);
    }
}
