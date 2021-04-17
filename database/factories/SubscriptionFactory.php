<?php

namespace Database\Factories;

use App\YourEdu\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

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
            'period' => ['MONTH','QUARTER','YEAR'][rand(0,2)],
            'for' => ['ALL','LEARNERS','PARENTS','FACILITATORS','PROFESSIONALS','SCHOOLS'][rand(0,5)],
            'amount' => $this->faker->numberBetween(200, 500),
        ];
    }
}
