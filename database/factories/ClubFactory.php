<?php

namespace Database\Factories;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClubFactory extends Factory {
    protected $model = Club::class;

    public function definition(): array {
        $adjectives = [
            'Debate',
            'Chess',
            'Drama',
            'Photography',
            'Astronomy',
            'Gardening',
            'E-Sports',
        ];
        
        return [
            'name' => fake()->unique()->randomElement($adjectives) . ' Club',
            'room' => 'R-' . fake()->numberBetween(100, 999),
            'founded_year' => fake()->year('-20 years'),
            'president_email' => fake()->unique()->safeEmail(),
        ];
    }
}