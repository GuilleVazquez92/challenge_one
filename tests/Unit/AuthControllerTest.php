<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_registers_a_user_successfully()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email'],
        ]);


        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'test',
        ]);

        $response->assertJsonFragment([
            'token' => $response->json('token'),
        ]);


        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('test', $user->name);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_validation_error_when_registering_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalidemail',
            'password' => '123',
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_logs_in_a_user_successfully()
    {
        $user = User::create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email'],
        ]);

        $response->assertJsonFragment([
            'token' => $response->json('token'),
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('test', $user->name);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_returns_error_when_login_with_invalid_credentials()
    {
        
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Incorrect credentials'
        ]);
    }
}
