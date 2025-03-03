<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Author;
use App\Models\User;

class AuthorControllerTest extends TestCase
{


    public function testCreateAuthor()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson(
            '/api/v1/authors',
            [
                'name' => 'Test Author',
                'nationality' => 'Paraguay',
                'birthdate' => '2025-03-02'
            ],
            [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name']);
    }

    public function testGetAuthors()
    {
        Author::factory()->count(3)->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->get('/api/v1/authors', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function testGetSingleAuthor()
    {
        $author = Author::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->get("/api/v1/authors/{$author->id}", [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertJson(['id' => $author->id, 'name' => $author->name]);
    }

    public function testUpdateAuthor()
    {
        $author = Author::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->putJson("/api/v1/authors/{$author->id}", [
            'name' => 'Updated Author',
            'birthdate' => '2025-01-01',
            'nationality' => 'Paraguay'
        ], [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertJson(['id' => $author->id, 'name' => 'Updated Author']);
    }

    public function testDeleteAuthor()
    {
        $author = Author::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->deleteJson("/api/v1/authors/{$author->id}",[], [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
