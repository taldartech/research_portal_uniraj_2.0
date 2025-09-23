<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hod_id',
        'dean_id',
    ];

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function dean()
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function drc()
    {
        return $this->hasOne(DRC::class);
    }
}
