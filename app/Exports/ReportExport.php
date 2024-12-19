<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements WithHeadings, FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $year;
    public $semester;
    public $teacherId;
    public $subjectId;

    public function __construct($year=null,$semester=null,$teacherId=null,$subjectId=null)
    {
        $this->year = $year;
        $this->semester = $semester;
        $this->teacherId = $teacherId;
        $this->subjectId = $subjectId;
    }

    public function collection() 
    {
        $query = "
                    SELECT year, semester, dbo.teachers.id AS teacher_id, dbo.teachers.last_name AS tea_lname,
                        dbo.teachers.first_name AS tea_fname , dbo.subjects.id AS subject_id, dbo.subjects.name AS subject_name, 
                        student_id, dbo.students.last_name AS stu_lname, dbo.students.first_name AS stu_fname, dbo.reports.path, mark
                    FROM dbo.teacher_to_subjects
                    INNER JOIN dbo.reports
                    ON dbo.teacher_to_subjects.id = dbo.reports.teacher_to_subjects_id
                    INNER JOIN dbo.subjects
                    ON dbo.subjects.id = dbo.teacher_to_subjects.subject_id
                    INNER JOIN dbo.teachers 
                    ON teacher_id = dbo.teachers.id
                    INNER JOIN dbo.students 
                    ON student_id  = dbo.students.id   
                ";
        if ($this->year != 'null') 
        {
            $query = $query." WHERE year = ". $this->year;
            if ($this->semester != 'null') 
            {
                $query = $query." AND semester = '". $this->semester."'";
            }
            if ($this->teacherId != 'null') 
            {
                $query = $query." AND teacher_id = ". $this->teacherId;
            }
    
            if ($this->subjectId !='null')
            {
                $query = $query." AND subjects.id = ". $this->subjectId;
            }
        } else {
            if ($this->semester !='null')
            {
                $query = $query." WHERE semester = ". $this->semester;

                if ($this->teacherId !='null')
                {
                    $query = $query." AND teacher_id = ". $this->teacherId;
                }
        
                if ($this->subjectId !='null')
                {
                    $query = $query." AND subjects.id = ". $this->subjectId;
                }
            } else {
                if ($this->teacherId !='null')
                {
                    $query = $query." WHERE teacher_id = ". $this->teacherId;

                    if ($this->subjectId !='null')
                    {
                        $query = $query." AND subjects.id = ". $this->subjectId;
                    } else {
                        if ($this->subjectId !='null')
                        {
                            $query = $query." WHERE subjects.id = ". $this->subjectId;
                        }
                    }
                }   
            } 
        }
        // dd($query);
        $query = DB::select($query);
        $colection = [];

        foreach($query as $row) {
            $item = [
                'Year' => $row->year,
                'Semester' => $row->semester,
                'teacherId' => $row->teacher_id,
                'TeacherName' => $row->tea_lname . $row->tea_fname,
                'SubjectId' => $row->subject_id,
                'SubjectName' => $row->subject_name,
                'StudentId' => $row->student_id,
                'StudentName' => $row->stu_lname . $row->stu_fname,
                'SubmitOrNot' => $row->path != '' ? true : false,
                'Mark' => $row->mark
            ];
            array_push($colection,$item);
        }
        return collect($colection);
    }
    public function headings():array
    {
        return [
            'Year',
            'Semester',
            'teacherId',
            'TeacherName',
            'SubjectId',
            'SubjectName',
            'StudentId',
            'StudentName',
            'SubmitOrNot',
            'Mark'
        ];
    } 
}
