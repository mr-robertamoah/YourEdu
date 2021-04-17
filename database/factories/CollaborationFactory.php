<?php

namespace Database\Factories;

use App\YourEdu\Collaboration;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollaborationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collaboration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'type' => "PAID"
        ];
    }
}
