<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UniqueNo;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MomoSchedule>
 */
class MomoScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "uploaded_by"=> FactoryHelper::getRandomModelId(User::class),
            "title"=> fake()->word(),
            "customer_message"=> fake()->word(),
            "disbursed_amount"=> fake()->numberBetween(1000, 200000),
            // "reference"=>FactoryHelper::getRandomModelId(UniqueNo::class),
            "treated"=>false,
        ];
    }
}
