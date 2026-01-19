<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomChangeRequest extends Model
{
    protected $fillable = [
        'student_id',
        'current_room_id',
        'reason',
        'status',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function currentRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'current_room_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function approve(User $admin, ?string $notes = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'admin_notes' => $notes,
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);
    }

    public function reject(User $admin, ?string $notes = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);
    }
}
