<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Rule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $context = collect([
            'phone number' => '[+]?(?:\(\d+(?:\.\d+)?\)|\d+(?:\.\d+)?)(?:[ -]?(?:\(\d+(?:\.\d+)?\)|\d+(?:\.\d+)?))*(?:[ ]?(?:x|ext)\.?[ ]?\d{1,5})?', // aus mobile
            'email' => '\\S+@\\S+\\.\\S+', //email
            'visa card' => '4\\d{12}(\\d{3})?', //visa card
            'master card' => '5[1-5]\\d{14}', //master card
            'amex card' => '3[4|7]\\d{13}', //amex card
            'visa master' => '(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14})', //visa master card
            'dinner card' => '(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})', //diner club card
            'discover card' => '(?:6011|650\\d)\\d{12}', //discover card
            'jcb card' => '(3\\d{4}|2100|1800)\\d{11}', //jcb card
            'swicth card' => '(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)', //switch
            'aus driver license' => '//',
        ]);

        return [
            'name' => $this->faker->name,
            'policy_id' => $this->faker->numberBetween(1, 1),
            'context' => $context->unique()->random(),
            'type' => $this->faker->randomElement(['regex', 'keywords']),
            'status' => $this->faker->randomElement(['block', 'warning']),
            'message' => $this->faker->sentence,
        ];
    }
}
