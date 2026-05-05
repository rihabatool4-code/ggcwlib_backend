<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lbstudent>
 */
class LbstudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             "testingone" => fake()->name(),
            "testingtwo" => fake()->safeEmail(),
            "testingthree" =>fake()->phoneNumber() ,
            "testingfour" =>Hash::make("password"),
        ];
    }
}
