<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'dispatch_number',
        'form_file_path',
        'status',
        'generated_by_da_id',
        'generated_at',
        'signed_by_dr_id',
        'signed_by_dr_at',
        'dr_signature_file',
        'signed_by_ar_id',
        'signed_by_ar_at',
        'ar_signature_file',
        'downloaded_at',
        'download_count',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'signed_by_dr_at' => 'datetime',
        'signed_by_ar_at' => 'datetime',
        'downloaded_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function generatedByDA()
    {
        return $this->belongsTo(User::class, 'generated_by_da_id');
    }

    public function signedByDR()
    {
        return $this->belongsTo(User::class, 'signed_by_dr_id');
    }

    public function signedByAR()
    {
        return $this->belongsTo(User::class, 'signed_by_ar_id');
    }

    // Helper methods
    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isSignedByDR()
    {
        return in_array($this->status, ['signed_by_dr', 'signed_by_ar', 'completed', 'downloaded']);
    }

    public function isSignedByAR()
    {
        return in_array($this->status, ['signed_by_ar', 'completed', 'downloaded']);
    }

    public function isCompleted()
    {
        return in_array($this->status, ['completed', 'downloaded']);
    }

    public function isDownloaded()
    {
        return $this->status === 'downloaded';
    }

    public function canBeDownloaded()
    {
        return $this->isCompleted();
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
        if ($this->status === 'completed') {
            $this->update([
                'status' => 'downloaded',
                'downloaded_at' => now()
            ]);
        }
    }
}
