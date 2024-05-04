<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasApiTokens, HasFactory;

   

    protected $fillable = ['name', 'matric_number', 'email', 'password', 'department_id', 'organization_id', 'role'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}
