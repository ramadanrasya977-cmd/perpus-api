<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'publisher' => fake()->optional()->company(),
            'isbn' => '978'.str_pad((string) fake()->unique()->numberBetween(0, 9_999_999_999), 10, '0', STR_PAD_LEFT),
            'published_year' => fake()->numberBetween(1980, now()->year),
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
