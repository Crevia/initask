<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DefinedUrl>
 */
class DefinedUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "original" => $this->faker->url(),
            "short" => $this->faker->url(),
            "status" => $this->faker->randomElement([1, 2, 3]),
            "hash" => $this->faker->randomAscii(),
        ];
    }
}
