<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     public function definition()
     {
         return [
             'student_id' => \App\Models\Student::factory(), // Assumes the Student model has a factory
             'subject_code' => $this->faker->unique()->numerify('T3B-###'),
             'name' => $this->faker->words(3, true),
             'description' => $this->faker->sentence,
             'instructor' => $this->faker->name,
             'schedule' => $this->faker->randomElement(['MW 7AM-12PM', 'TTH 1PM-6PM']),
             'prelims' => $this->faker->randomFloat(2, 1, 5),
             'midterms' => $this->faker->randomFloat(2, 1, 5),
             'pre_finals' => $this->faker->randomFloat(2, 1, 5),
             'finals' => $this->faker->randomFloat(2, 1, 5),
             'average_grade' => 0, // This will be calculated later
             'remarks' => '', // This will be set based on average_grade
             'date_taken' => $this->faker->date('Y-m-d'),
         ];
     }
}
