<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasApiTokens, HasFactory;
    


    protected $fillable = ['name', 'email', 'phone', 'address', 'supervisor_name',  'password', 'supervisor_email', 'role'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
