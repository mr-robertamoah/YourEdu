<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\Learner;
use Illuminate\Database\Eloquent\Factories\Factory;

class LearnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Learner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name
        ];
    }
}
