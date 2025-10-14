<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder {
    public function run(): void {
        Club::create([
            'name' => 'Robotics & AI Club',
            'room' => 'R-101',
            'founded_year' => 2015,
            'president_email' => 'president@robotics.club'
        ]);

        Club::factory(7)->create();
    }
}