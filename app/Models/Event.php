<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model {
    use HasFactory;

    protected $fillable = [
        'club_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'venue',
        'expected_audience',
    ];

    public function club(): BelongsTo {
        return $this->belongsTo(Club::class);
    }
}