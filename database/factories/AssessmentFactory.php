<?php

namespace Database\Factories;

use App\YourEdu\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assessment::class;

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
            'type' => ['PUBLIC', 'PRIVATE'][random_int(0,1)],
            'published_at' => now()->toDateTimeString(),
            'due_at' => now()->addDays(10)->toDateTimeString(),
        ];
    }
}
