<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Support\Facades\{Http, Validator};
use Tests\TestCase;
use App\Models\Author;
use App\Models\User;
use App\Http\Requests\V1\BookRequest;


class BookControllerTest extends TestCase
{

    public function test_index_returns_books()
    {
        $books = Book::factory()->count(2)->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->getJson('/api/v1/books', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'isbn',
                    'created_at',
                    'updated_at',
                    'published_date',
                    'author' => [
                        'id',
                    ],
                ]
            ]
        ]);
    }

    public function test_store_creates_book()
    {
        $author = Author::factory()->create();
        $data = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'published_date' => '2025-03-02',
            'isbn' =>  1234
        ];

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson("/api/v1/books", $data, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertJson([
            'title' => $data['title'],
            'isbn' => $data['isbn'],
            'published_date' => $data['published_date'],
            'author' => [
                'id' => $data['author_id'],
            ],
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'isbn' => 1234,
            'published_date' => '2025-03-02',
        ]);
    }


    public function test_show_returns_book()
    {
        $book = Book::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->getJson('api/v1/books/' . $book->id, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'title',
                'isbn',
                'published_date',
                'author' => [
                    'id',
                ]
            ]);
    }

    public function test_update_updates_book()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $book = Book::factory()->create();

        $updateData = [
            'title' => 'Updated Book Title',
            'isbn' => $book->isbn + 1,
            'author_id' => $book->author_id,
            'published_date' => '2025-01-01',
        ];

        $response = $this->putJson("/api/v1/books/{$book->id}", $updateData, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);


        $response->assertStatus(200);
        $response->assertJson([
            'title' => $updateData['title'],
            'isbn' => $updateData['isbn'],
            'published_date' => $updateData['published_date'],
            'author' => [
                'id' => $updateData['author_id'],
            ],
        ]);
    }



    public function test_destroy_deletes_book()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $book = Book::factory()->create();

        $updateData = [
            'title' => 'Updated Book Title',
            'isbn' => $book->isbn + 1,
            'author_id' => $book->author_id,
            'published_date' => '2025-01-01',
        ];

        $response = $this->deleteJson("/api/v1/books/{$book->id}", $updateData, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);


        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Book deleted successfully'
        ]);
    }


    public function test_store_validation_fails()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $bookData = [
            'title' => '',
            'isbn' => 'abc',
            'author_id' => null,
            'published_date' => 'invalid-date',
        ];

        $request = new BookRequest();
        $validator = Validator::make($bookData, $request->rules());

        $this->assertTrue($validator->fails());

        $response = $this->postJson('/api/v1/books', $bookData, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'isbn', 'author_id', 'published_date']);
    }

    public function test_store_handles_exception()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $author = Author::factory()->create();

        $bookData = [
            'title' => 'Test Book Title',
            'isbn' => '12346',
            'author_id' => $author->id,
            'published_date' => '2025-01-01',
        ];

        $response = $this->postJson('/api/v1/books', $bookData, [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'title' => $bookData['title'],
            'isbn' => $bookData['isbn'],
            'published_date' => $bookData['published_date'],
            'author' => [
                'id' => $bookData['author_id'],
            ],
        ]);
    }
}
