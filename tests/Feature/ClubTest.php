<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_all_clubs(): void
    {
        Club::factory()->count(3)->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/clubs');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'room', 'founded_year', 'students_count', 'events_count']
                     ]
                 ]);
    }

    public function test_authenticated_user_can_create_club(): void
    {
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/clubs', [
                             'name' => 'Test Club',
                             'room' => 'A101',
                             'founded_year' => 2020,
                             'president_email' => 'president@test.com',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'name', 'room', 'founded_year']);

        $this->assertDatabaseHas('clubs', [
            'name' => 'Test Club',
            'room' => 'A101',
        ]);
    }

    public function test_authenticated_user_can_get_single_club(): void
    {
        $club = Club::factory()->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/clubs/{$club->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'name', 'students', 'events']);
    }

    public function test_authenticated_user_can_update_club(): void
    {
        $club = Club::factory()->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->putJson("/api/clubs/{$club->id}", [
                             'name' => 'Updated Club Name',
                             'room' => 'B202',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['name' => 'Updated Club Name']);

        $this->assertDatabaseHas('clubs', [
            'id' => $club->id,
            'name' => 'Updated Club Name',
        ]);
    }

    public function test_authenticated_user_can_delete_club(): void
    {
        $club = Club::factory()->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->deleteJson("/api/clubs/{$club->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('clubs', ['id' => $club->id]);
    }

    public function test_authenticated_user_can_restore_club(): void
    {
        $club = Club::factory()->create();
        $club->delete(); // Soft delete
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson("/api/clubs/{$club->id}/restore");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Club restored successfully']);

        $this->assertDatabaseHas('clubs', [
            'id' => $club->id,
            'deleted_at' => null,
        ]);
    }

    public function test_club_search_functionality(): void
    {
        Club::factory()->create(['name' => 'Chess Club']);
        Club::factory()->create(['name' => 'Drama Club']);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/clubs?search=Chess');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Chess Club', $data[0]['name']);
    }

    public function test_club_filtering_by_founded_year(): void
    {
        Club::factory()->create(['founded_year' => 2020]);
        Club::factory()->create(['founded_year' => 2021]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/clubs?founded_year=2020');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals(2020, $data[0]['founded_year']);
    }
}

