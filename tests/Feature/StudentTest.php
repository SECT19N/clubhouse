<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_all_students(): void
    {
        Student::factory()->count(5)->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/students');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'first_name', 'last_name', 'email']
                     ]
                 ]);
    }

    public function test_authenticated_user_can_create_student(): void
    {
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/students', [
                             'first_name' => 'John',
                             'last_name' => 'Doe',
                             'email' => 'john.doe@example.com',
                             'gender' => 'M',
                             'date_of_birth' => '2005-06-15',
                             'graduation_year' => 2025,
                             'gpa' => 3.75,
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'first_name', 'last_name', 'email']);

        $this->assertDatabaseHas('students', [
            'email' => 'john.doe@example.com',
        ]);
    }

    public function test_student_search_functionality(): void
    {
        Student::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        Student::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/students?search=John');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('John', $data[0]['first_name']);
    }

    public function test_student_filtering_by_graduation_year(): void
    {
        Student::factory()->create(['graduation_year' => 2025]);
        Student::factory()->create(['graduation_year' => 2026]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/students?graduation_year=2025');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals(2025, $data[0]['graduation_year']);
    }

    public function test_student_filtering_by_gpa_range(): void
    {
        Student::factory()->create(['gpa' => 3.5]);
        Student::factory()->create(['gpa' => 2.5]);
        Student::factory()->create(['gpa' => 4.0]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/students?gpa_min=3.0&gpa_max=3.8');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals(3.5, $data[0]['gpa']);
    }
}

