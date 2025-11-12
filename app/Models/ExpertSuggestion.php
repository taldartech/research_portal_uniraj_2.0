<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertSuggestion extends Model
{
    protected $fillable = [
        'thesis_submission_id',
        'supervisor_id',
        'name',
        'email',
        'mobile_no',
        'address',
        'state',
    ];

    public function thesisSubmission()
    {
        return $this->belongsTo(ThesisSubmission::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
