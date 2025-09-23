<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalSignature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'signature_name',
        'signature_file',
        'signature_data',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getSignatureUrl()
    {
        if ($this->signature_file) {
            return asset('storage/' . $this->signature_file);
        }
        return null;
    }

    public function getSignaturePath()
    {
        if ($this->signature_file) {
            return storage_path('app/public/' . $this->signature_file);
        }
        return null;
    }

    public function isSignatureFileExists()
    {
        $path = $this->getSignaturePath();
        return $path && file_exists($path);
    }
}
