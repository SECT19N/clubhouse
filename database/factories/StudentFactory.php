<?php
namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory {
    protected $model = Student::class;

    public function definition(): array {
        $gender = fake()->randomElement(['M','F']);

        return [
            'first_name'      => fake()->firstName($gender === 'M' ? 'male' : 'female'),
            'last_name'       => fake()->lastName(),
            'email'           => fake()->unique()->safeEmail(),
            'gender'          => $gender,
            'date_of_birth'   => fake()->dateTimeBetween('-22 years','-15 years')->format('Y-m-d'),
            'graduation_year' => (int) date('Y') + fake()->numberBetween(0, 3),
            'gpa'             => fake()->randomFloat(2, 2, 4),
        ];
    }
}