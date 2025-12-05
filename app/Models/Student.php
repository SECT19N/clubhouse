<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model {
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'graduation_year',
        'gpa',
        'user_id',
    ];

    public function clubs(): BelongsToMany {
        return $this->belongsToMany(Club::class)
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    /**
     * Get the user associated with this student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}