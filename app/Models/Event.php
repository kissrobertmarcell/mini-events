<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'event_date',
        'limit',
        'image',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    protected $appends = [
        'available_spots',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signups(): HasMany
    {
        return $this->hasMany(EventSignup::class);
    }

    public function getAvailableSpotsAttribute(): int
    {
        $signupCount = $this->relationLoaded('signups')
            ? $this->signups->count()
            : $this->signups()->count();

        return max(0, $this->limit - $signupCount);
    }

    public function isFull(): bool
    {
        $signupCount = $this->relationLoaded('signups')
            ? $this->signups->count()
            : $this->signups()->count();

        return $signupCount >= $this->limit;
    }

    public function isUserSignedUp($userId): bool
    {
        if ($this->relationLoaded('signups')) {
            return $this->signups->contains('user_id', $userId);
        }

        return $this->signups()
            ->where('user_id', $userId)
            ->exists();
    }
}
