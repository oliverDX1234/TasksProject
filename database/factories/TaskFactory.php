<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;


class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'status' => "open",
            'user_id' => User::all()->random()->id,
            'expiration_date' => Carbon::now(),
            "description" => $this->faker->paragraph()
        ];
    }
}
