<?php

namespace Database\Factories;

use App\Model;
use App\YourEdu\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_names' => implode(', ', [$this->faker->name, $this->faker->name]),
            'title' => $this->faker->sentence,
            'about' => $this->faker->paragraph,
        ];
    }
}
