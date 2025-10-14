<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'room',
        'founded_year',
        'president_email'
    ];

    public function students(): BelongsToMany {
        return $this->belongsToMany(Student::class)
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function events(): HasMany {
        return $this->hasMany(Event::class);
    }
}