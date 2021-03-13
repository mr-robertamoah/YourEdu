<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\PossibleAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PossibleAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PossibleAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'option' => $this->faker->word
        ];
    }
}
