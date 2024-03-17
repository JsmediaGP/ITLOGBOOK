<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['logbook_id', 'comment', 'week_number', 'organization_id'];

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }
}
