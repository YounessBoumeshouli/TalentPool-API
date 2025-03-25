<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Announcement;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruiter_can_create_announcement()
    {
        $recruiter = User::factory()->create(['role' => 'recruiter']);
        $token = auth()->login($recruiter);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a skilled developer',
            'company' => 'Tech Corp',
            'location' => 'Remote',
            'status' => 'active'
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/announcements', $announcementData);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'announcement']);
    }

    public function test_candidate_cannot_create_announcement()
    {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $token = auth()->login($candidate);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a skilled developer',
            'company' => 'Tech Corp',
            'location' => 'Remote',
            'status' => 'active'
        ];

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/announcements', $announcementData);

        $response->assertStatus(403);
    }
}
