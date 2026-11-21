<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Pembaca Baru',
            'email' => 'reader@example.com',
            'password' => 'strong-password',
            'password_confirmation' => 'strong-password',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user.email', 'reader@example.com')
            ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email'], 'token']]);

        $this->assertDatabaseHas('users', ['email' => 'reader@example.com']);
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_user_can_log_in_and_receive_a_token(): void
    {
        User::factory()->create([
            'email' => 'reader@example.com',
            'password' => 'strong-password',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'reader@example.com',
            'password' => 'strong-password',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.email', 'reader@example.com')
            ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_with_an_incorrect_password_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'reader@example.com',
            'password' => 'correct-password',
        ]);

        $this->postJson('/api/login', [
            'email' => 'reader@example.com',
            'password' => 'wrong-password',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)->postJson('/api/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logout berhasil.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_protected_endpoints_reject_requests_without_a_token(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
        $this->getJson('/api/books')->assertUnauthorized();
    }

    public function test_api_requests_without_json_accept_header_still_return_json_errors(): void
    {
        $this->get('/api/books')
            ->assertUnauthorized()
            ->assertHeader('content-type', 'application/json')
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_authenticated_user_can_list_books(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Book::factory()->count(3)->create();

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_user_can_search_books_by_title_or_author(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Book::factory()->create(['title' => 'Laravel API Praktis', 'author' => 'Ayu Rahma']);
        Book::factory()->create(['title' => 'Basis Data', 'author' => 'Budi Santoso']);

        $this->getJson('/api/books?search=Laravel')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Laravel API Praktis');

        $this->getJson('/api/books?search=Budi')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.author', 'Budi Santoso');
    }

    public function test_user_can_add_a_book(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/books', $this->bookData())
            ->assertCreated()
            ->assertJsonPath('data.title', 'Pemrograman Laravel');

        $this->assertDatabaseHas('books', ['isbn' => '9786021234567']);
    }

    public function test_duplicate_isbn_is_rejected(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Book::factory()->create(['isbn' => '9786021234567']);

        $this->postJson('/api/books', $this->bookData())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('isbn');
    }

    public function test_user_can_view_a_book(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $book = Book::factory()->create();

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $book->id);
    }

    public function test_user_can_update_a_book(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $book = Book::factory()->create();

        $this->patchJson("/api/books/{$book->id}", ['title' => 'Judul Diperbarui'])
            ->assertOk()
            ->assertJsonPath('data.title', 'Judul Diperbarui');

        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Judul Diperbarui']);
    }

    public function test_user_can_replace_a_book_with_put(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $book = Book::factory()->create();
        $replacement = $this->bookData();
        $replacement['isbn'] = '9786027654321';

        $this->putJson("/api/books/{$book->id}", $replacement)
            ->assertOk()
            ->assertJsonPath('data.title', 'Pemrograman Laravel')
            ->assertJsonPath('data.isbn', '9786027654321');
    }

    public function test_user_can_delete_a_book(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $book = Book::factory()->create();

        $this->deleteJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Buku berhasil dihapus.');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * @return array<string, mixed>
     */
    private function bookData(): array
    {
        return [
            'title' => 'Pemrograman Laravel',
            'author' => 'Ayu Rahma',
            'publisher' => 'Penerbit Teknologi',
            'isbn' => '9786021234567',
            'published_year' => 2024,
            'stock' => 5,
            'description' => 'Buku pengantar pengembangan API.',
        ];
    }
}
