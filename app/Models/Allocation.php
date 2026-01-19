<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Allocation extends Model
{
    protected $fillable = [
        'student_id',
        'room_id',
        'hostel_id',
        'allocation_date',
        'status',
        'allocation_type',
        'academic_year',
    ];

    protected $casts = [
        'allocation_date' => 'date',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeAutoAllocated($query)
    {
        return $query->where('allocation_type', 'auto');
    }

    public function scopeManualAllocated($query)
    {
        return $query->where('allocation_type', 'manual');
    }
}
