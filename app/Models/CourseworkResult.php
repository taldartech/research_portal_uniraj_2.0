<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseworkResult extends Model
{
    protected $fillable = [
        'scholar_id',
        'uploaded_by',
        'marksheet_file',
        'exam_date',
        'result',
        'remarks',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    /**
     * Get the scholar that owns the coursework result
     */
    public function scholar(): BelongsTo
    {
        return $this->belongsTo(Scholar::class);
    }

    /**
     * Get the user (HOD) who uploaded this result
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if result is pass
     */
    public function isPass(): bool
    {
        return $this->result === 'pass';
    }

    /**
     * Check if result is fail
     */
    public function isFail(): bool
    {
        return $this->result === 'fail';
    }
}
