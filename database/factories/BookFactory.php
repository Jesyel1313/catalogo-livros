<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Regex pattern for generating ISBN-13.
        $isbnPattern = '[0-9]{3}-[0-9]-[0-9]{2}-[0-9]{6}-[0-9]';

        return [
            'name' => ucwords(fake()->words(3, true)),
            'author_id' => fake()->numberBetween(1, 100),
            'category_id' => fake()->numberBetween(1, 100),
            'pages' => fake()->numberBetween(10, 1000),
            'year' => fake()->year(),
            'isbn' => fake()->regexify($isbnPattern),
            'synopsis' => fake()->paragraph()
        ];
    }
}
