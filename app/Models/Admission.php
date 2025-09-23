<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'department_id',
        'merit_list_file',
        'admission_date',
        'status',
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
