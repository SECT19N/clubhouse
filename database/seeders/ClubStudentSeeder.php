<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ClubStudentSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $clubs    = Club::pluck('id');        // [1,2,3 …]
        $students = Student::pluck('id');     // [1,2,3 … 50]

        // how many memberships you want
        $memberships = min(100, $clubs->count() * $students->count());

        for ($i = 0; $i < $memberships; $i++) {
            $clubId    = $clubs->random();
            $studentId = $students->random();

            // avoid duplicate pairs
            $exists = DB::table('club_student')
                        ->where('club_id', $clubId)
                        ->where('student_id', $studentId)
                        ->exists();

            if (! $exists) {
                DB::table('club_student')->insert([
                    'club_id'    => $clubId,
                    'student_id' => $studentId,
                    'role'       => fake()->randomElement(['member','member','member','treasurer','president']),
                    'joined_at'  => now()->subDays(rand(0, 365)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}