<?php

namespace Database\Factories\Course;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryCourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'seo_title' => fake()->word(),
            'seo_description' => fake()->text(),
            'seo_keywords' => fake()->word(),
            'parent_id' => null,
            'tag_id' => rand(1,5),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
            // ''
        ];


    }
}
