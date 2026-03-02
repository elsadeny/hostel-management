<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomApplication extends Model
{
    protected $fillable = [
        'student_id',
        'preferred_hostel',
        'room_type',
        'special_needs',
        'notes',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
        'academic_year',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
