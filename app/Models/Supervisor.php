<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisor extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone', 'password', 'organization_id'];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
