<?php

namespace Database\Factories;

use App\YourEdu\Extracurriculum;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtracurriculumFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Extracurriculum::class;

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
        ];
    }
}
