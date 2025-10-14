<?php
namespace Database\Factories;

use App\Models\Event;
use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory {
    protected $model = Event::class;

    public function definition(): array {
        $start = fake()->dateTimeBetween('-2 months', '+3 months');
        $end   = fake()->optional(70)->dateTimeBetween($start, $start->format('Y-m-d H:i:s') . ' +4 hours');

        return [
            'club_id'           => Club::factory(),   // auto-create if not provided
            'title'             => ucfirst(fake()->words(3, true)),
            'description'       => fake()->optional(60)->paragraph(3),
            'start_time'        => $start,
            'end_time'          => $end,
            'venue'             => fake()->optional()->randomElement(['Auditorium', 'Lab 3', 'Library Hall', 'Sports Field']),
            'expected_audience' => fake()->numberBetween(10, 300),
        ];
    }
}