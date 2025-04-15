<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph,
            'status' => $this->faker->randomElement(['TODO', 'IN_PROGRESS']),
            'importance' => $this->faker->numberBetween(1, 5),
            'deadline' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
        ];
    }
}
