<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'addedby_type' => 'learner',
            'addedby_id' => '1',
            'mime' => 'image/png',
            'name' => 'good.png',
            'path' => "images/good{$this->faker->numberBetween()}.png",
            'size' => "{$this->faker->numberBetween()}",
        ];
    }
}
