<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $table = 'Students';
    protected $fillable = [
        'last_name','first_name', 'student_code', 'department', 'faculty', 'address', 'phone', 'note'
    ];

    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class);
    }
}
