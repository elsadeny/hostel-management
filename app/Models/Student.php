<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'full_name',
        'gender',
        'study_level',
        'department',
        'year',
        'phone',
        'email',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function allocation(): HasOne
    {
        return $this->hasOne(Allocation::class)->where('status', 'active');
    }

    public function allAllocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    public function roomChangeRequests(): HasMany
    {
        return $this->hasMany(RoomChangeRequest::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    // Scopes
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByStudyLevel($query, $studyLevel)
    {
        return $query->where('study_level', $studyLevel);
    }

    public function scopeUnallocated($query)
    {
        return $query->doesntHave('allocation');
    }

    // Methods
    public function hasActiveAllocation(): bool
    {
        return $this->allocation()->exists();
    }

    public function getCurrentRoom()
    {
        return $this->allocation?->room;
    }

    public function getCurrentHostel()
    {
        return $this->allocation?->hostel;
    }
}
