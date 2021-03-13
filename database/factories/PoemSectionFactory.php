<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\PoemSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class PoemSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PoemSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->sentence,
            'poem_id' => 1
        ];
    }
}
