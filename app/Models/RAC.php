<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RAC extends Model
{
    use HasFactory;

    protected $table = 'racs';

    protected $fillable = [
        'scholar_id',
        'supervisor_id',
        'minutes_file',
        'formed_date',
        'status',
    ];

    protected $casts = [
        'formed_date' => 'date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function drcs()
    {
        return $this->belongsToMany(DRC::class, 'drc_rac');
    }

    public function department()
    {
        // Assuming a RAC is associated with a department through the scholar's admission
        return $this->hasOneThrough(Department::class, Scholar::class, 'id', 'id', 'scholar_id', 'department_id');
    }
}
