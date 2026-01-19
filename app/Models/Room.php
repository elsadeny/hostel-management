<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'hostel_id',
        'room_number',
        'capacity',
        'current_occupancy',
        'floor',
        'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'floor' => 'integer',
    ];

    // Relationships
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    public function roomChangeRequests(): HasMany
    {
        return $this->hasMany(RoomChangeRequest::class, 'current_room_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->whereColumn('current_occupancy', '<', 'capacity');
    }

    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor', $floor);
    }

    // Methods
    public function isFull(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }

    public function addOccupant(): bool
    {
        if ($this->isFull()) {
            return false;
        }

        $this->increment('current_occupancy');

        if ($this->isFull()) {
            $this->update(['status' => 'full']);
        }

        return true;
    }

    public function removeOccupant(): bool
    {
        if ($this->current_occupancy <= 0) {
            return false;
        }

        $this->decrement('current_occupancy');

        if (!$this->isFull() && $this->status !== 'maintenance') {
            $this->update(['status' => 'available']);
        }

        return true;
    }

    public function getAvailableSpace(): int
    {
        return $this->capacity - $this->current_occupancy;
    }
}
