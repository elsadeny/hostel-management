<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'total_rooms',
        'total_capacity',
        'address',
        'description',
        'status',
    ];

    protected $casts = [
        'total_rooms' => 'integer',
        'total_capacity' => 'integer',
    ];

    // Relationships
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    // Scopes
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active');
    }

    // Methods
    public function getAvailableRooms()
    {
        return $this->rooms()->where('status', 'available')->get();
    }

    public function getOccupancyRate()
    {
        $totalCapacity = $this->total_capacity;
        $currentOccupancy = $this->rooms()->sum('current_occupancy');

        return $totalCapacity > 0 ? ($currentOccupancy / $totalCapacity) * 100 : 0;
    }

    public function getCurrentOccupancy()
    {
        return $this->rooms()->sum('current_occupancy');
    }
}
