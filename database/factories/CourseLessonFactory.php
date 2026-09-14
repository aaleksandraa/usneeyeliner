<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseLessonFactory extends Factory
{
    public function definition(): array
    {
        return ['course_id' => Course::factory(), 'title' => fake()->sentence(4), 'description' => fake()->paragraph(), 'vimeo_url' => 'https://vimeo.com/'.fake()->numberBetween(100000000, 999999999), 'sort_order' => fake()->numberBetween(1, 20)];
    }
}
