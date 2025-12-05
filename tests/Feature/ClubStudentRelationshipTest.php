<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubStudentRelationshipTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_add_student_to_club(): void
    {
        $club = Club::factory()->create();
        $student = Student::factory()->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/clubs/{$club->id}/students", [
                             'student_id' => $student->id,
                             'role' => 'member',
                         ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Student added to club successfully']);

        $this->assertDatabaseHas('club_student', [
            'club_id' => $club->id,
            'student_id' => $student->id,
            'role' => 'member',
        ]);
    }

    public function test_authenticated_user_can_get_club_students(): void
    {
        $club = Club::factory()->create();
        $student = Student::factory()->create();
        $club->students()->attach($student->id, ['role' => 'member']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/clubs/{$club->id}/students");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'club_id',
                     'club_name',
                     'students',
                     'total_students',
                 ]);
    }

    public function test_authenticated_user_can_remove_student_from_club(): void
    {
        $club = Club::factory()->create();
        $student = Student::factory()->create();
        $club->students()->attach($student->id, ['role' => 'member']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/clubs/{$club->id}/students/{$student->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Student removed from club successfully']);

        $this->assertDatabaseMissing('club_student', [
            'club_id' => $club->id,
            'student_id' => $student->id,
        ]);
    }

    public function test_authenticated_user_can_update_student_role_in_club(): void
    {
        $club = Club::factory()->create();
        $student = Student::factory()->create();
        $club->students()->attach($student->id, ['role' => 'member']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/clubs/{$club->id}/students/{$student->id}/role", [
                             'role' => 'treasurer',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Student role updated successfully']);

        $this->assertDatabaseHas('club_student', [
            'club_id' => $club->id,
            'student_id' => $student->id,
            'role' => 'treasurer',
        ]);
    }

    public function test_cannot_add_duplicate_student_to_club(): void
    {
        $club = Club::factory()->create();
        $student = Student::factory()->create();
        $club->students()->attach($student->id, ['role' => 'member']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/clubs/{$club->id}/students", [
                             'student_id' => $student->id,
                             'role' => 'member',
                         ]);

        $response->assertStatus(409)
                 ->assertJson(['message' => 'Student is already a member of this club.']);
    }

    public function test_authenticated_user_can_get_student_clubs(): void
    {
        $student = Student::factory()->create();
        $club1 = Club::factory()->create();
        $club2 = Club::factory()->create();
        $student->clubs()->attach($club1->id, ['role' => 'member']);
        $student->clubs()->attach($club2->id, ['role' => 'president']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/students/{$student->id}/clubs");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'student_id',
                     'student_name',
                     'clubs',
                     'total_clubs',
                 ]);

        $this->assertCount(2, $response->json('clubs'));
    }
}

