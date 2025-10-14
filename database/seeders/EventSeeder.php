<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Club;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder {
    public function run(): void {
        $clubs = Club::pluck('id');

        foreach ($clubs as $clubId) {
            Event::factory(rand(2, 9))->create([
                'club_id' => $clubId
            ]);
        }
    }
}