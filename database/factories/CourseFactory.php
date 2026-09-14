<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return ['title' => $title, 'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999), 'description' => fake()->paragraphs(2, true), 'status' => 'active'];
    }
}
