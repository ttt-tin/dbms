<?php

namespace App\Jobs;

use App\Imports\SubjectsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportSubjectsCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $path;
    public $subjectImport;
    /**
     * Create a new job instance.
     */
    public function __construct($path, $subjectImport)
    {
        $this->path = $path;
        $this->subjectImport = $subjectImport;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::update('UPDATE dbo.imports SET status = 1 WHERE id = ?', [$this->subjectImport->id]);

        Excel::import(new SubjectsImport, $this->path);

        DB::update('UPDATE dbo.imports SET status = 2 WHERE id = ?', [$this->subjectImport->id]);
    }

    public function fail(Throwable $exception)
    {
        DB::update('UPDATE dbo.imports SET status = 3 WHERE id = ?', [$this->subjectImport->id]);
    }
}
