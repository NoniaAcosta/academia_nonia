<?php

namespace Database\Factories;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
     */
    protected $model = Course::class;
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+3 months');

        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }
}
