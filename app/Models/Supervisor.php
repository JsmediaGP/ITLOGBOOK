<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Supervisor extends Model
{
    // use HasApiTokens, HasFactory;
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = ['name', 'email', 'phone', 'password', 'organization_id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
