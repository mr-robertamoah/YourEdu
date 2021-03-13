<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->sentence,
            'hint' => $this->faker->paragraph,
            'published_at' => now()->toDateTimeString()
        ];
    }
}
