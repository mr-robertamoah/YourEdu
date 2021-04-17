<?php

namespace Database\Factories;

use App\YourEdu\Flag;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Flag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reason' => $this->faker->sentence,
            'status' => ['PENDING','APPROVED','CANCELLED'][rand(0,2)]
        ];
    }
}
