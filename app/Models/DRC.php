<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DRC extends Model
{
    use HasFactory;

    protected $table = 'drcs';

    protected $fillable = [
        'department_id',
        'hod_id',
        'minutes_file',
        'meeting_date',
        'status',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function racs()
    {
        return $this->belongsToMany(RAC::class, 'drc_rac');
    }
}
