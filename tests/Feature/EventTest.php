<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): string
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_authenticated_user_can_get_all_events(): void
    {
        $club = Club::factory()->create();
        Event::factory()->count(3)->create(['club_id' => $club->id]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/events');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'start_time', 'club']
                     ]
                 ]);
    }

    public function test_authenticated_user_can_create_event(): void
    {
        $club = Club::factory()->create();
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/events', [
                             'club_id' => $club->id,
                             'title' => 'Test Event',
                             'description' => 'Test Description',
                             'start_time' => now()->addDays(7)->format('Y-m-d H:i:s'),
                             'end_time' => now()->addDays(7)->addHours(2)->format('Y-m-d H:i:s'),
                             'venue' => 'Main Hall',
                             'expected_audience' => 100,
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'club_id']);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'club_id' => $club->id,
        ]);
    }

    public function test_event_filtering_by_club(): void
    {
        $club1 = Club::factory()->create();
        $club2 = Club::factory()->create();
        Event::factory()->create(['club_id' => $club1->id]);
        Event::factory()->create(['club_id' => $club2->id]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson("/api/events?club_id={$club1->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($club1->id, $data[0]['club_id']);
    }

    public function test_event_filtering_upcoming_events(): void
    {
        $club = Club::factory()->create();
        Event::factory()->create([
            'club_id' => $club->id,
            'start_time' => now()->addDays(7),
        ]);
        Event::factory()->create([
            'club_id' => $club->id,
            'start_time' => now()->subDays(7),
        ]);
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/events?upcoming=true');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertTrue(strtotime($data[0]['start_time']) > now()->timestamp);
    }
}

