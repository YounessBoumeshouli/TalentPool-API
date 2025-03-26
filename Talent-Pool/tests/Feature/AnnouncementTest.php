<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Announcement;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruiter_can_create_announcement()
    {
        // Créer un recruteur et générer un token
        $recruiter = User::factory()->create([
            'role' => 'recruiter'
        ]);

        // Connecter l'utilisateur et obtenir le token
        $token = JWTAuth::fromUser($recruiter);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a skilled developer',
            'company' => 'Tech Corp',
            'location' => 'Remote',
            'status' => 'active'
        ];

        // Faire la requête avec le token
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/announcements', $announcementData);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'announcement']);
    }

    public function test_candidate_cannot_create_announcement()
    {
        // Créer un candidat et générer un token
        $candidate = User::factory()->create([
            'role' => 'candidate'
        ]);

        // Connecter l'utilisateur et obtenir le token
        $token = JWTAuth::fromUser($candidate);

        $announcementData = [
            'title' => 'Software Engineer',
            'description' => 'We are looking for a skilled developer',
            'company' => 'Tech Corp',
            'location' => 'Remote',
            'status' => 'active'
        ];

        // Faire la requête avec le token
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/announcements', $announcementData);

        $response->assertStatus(403);
    }
}
