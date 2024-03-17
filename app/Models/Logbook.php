<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'work_done', 'attachment', 'created_at'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

