<?php

namespace Database\Factories;

use App\YourEdu\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicYearFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AcademicYear::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence,
            'start_date' => now()->subYear()->toDateTimeString(),
            'end_date' => now()->addYear()->toDateTimeString(),
        ];
    }
}
