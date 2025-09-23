<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'designation',
        'research_specialization',
        'supervisor_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedScholars()
    {
        return $this->belongsToMany(Scholar::class, 'supervisor_assignments');
    }

    public function capacityIncreaseRequests()
    {
        return $this->hasMany(SupervisorCapacityIncreaseRequest::class);
    }

    /**
     * Get the maximum number of scholars this supervisor can handle
     */
    public function getScholarLimit()
    {
        $baseLimit = match($this->supervisor_type) {
            'assistant' => 4,
            'associate' => 6,
            'professor' => 8,
            default => 4,
        };

        // Check if there's an approved capacity increase request
        $approvedIncrease = $this->capacityIncreaseRequests()
            ->where('status', 'approved')
            ->latest()
            ->first();

        if ($approvedIncrease) {
            return $approvedIncrease->requested_capacity;
        }

        return $baseLimit;
    }

    /**
     * Get the current number of assigned scholars
     */
    public function getCurrentScholarCount()
    {
        return $this->assignedScholars()->wherePivot('status', 'assigned')->count();
    }

    /**
     * Check if this supervisor can accept more scholars
     */
    public function canAcceptMoreScholars()
    {
        return $this->getCurrentScholarCount() < $this->getScholarLimit();
    }

    /**
     * Get the supervisor type display name
     */
    public function getSupervisorTypeDisplayAttribute()
    {
        return match($this->supervisor_type) {
            'assistant' => 'Assistant Supervisor',
            'associate' => 'Associate Supervisor',
            'professor' => 'Professor',
            default => 'Assistant Supervisor',
        };
    }

    /**
     * Check if supervisor has a pending capacity increase request
     */
    public function hasPendingCapacityIncreaseRequest()
    {
        return $this->capacityIncreaseRequests()->whereIn('status', [
            'pending_da', 'pending_so', 'pending_ar', 'pending_dr', 'pending_hvc'
        ])->exists();
    }

    /**
     * Get the base capacity without any increases
     */
    public function getBaseScholarLimit()
    {
        return match($this->supervisor_type) {
            'assistant' => 4,
            'associate' => 6,
            'professor' => 8,
            default => 4,
        };
    }
}
