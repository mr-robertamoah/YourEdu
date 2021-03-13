<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\Riddle;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiddleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Riddle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_names' => implode(', ', [$this->faker->name, $this->faker->name]),
            'body' => $this->faker->paragraph,
            'published_at' => now()->toDateTimeString(),
            'score_over' => $this->faker->numberBetween(5, 100),
        ];
    }
}
