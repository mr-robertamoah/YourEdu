<?php

namespace Database\Factories;

use App\YourEdu\AssessmentSection;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentSectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssessmentSection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'assessment_id' => random_int(1,100),
            'name' => $this->faker->word,
            'instruction' => $this->faker->sentence,
            'auto_mark' => [true, false][random_int(0,1)],
            'random' => [true, false][random_int(0,1)],
            'answer_type' => ['TRUE_FALSE','LONG_ANSWER','SHORT_ANSWER','IMAGE','VIDEO','AUDIO',
            'OPTION','NUMBER','FLOW','ARRANGE'][random_int(0,9)],
        ];
    }
}
