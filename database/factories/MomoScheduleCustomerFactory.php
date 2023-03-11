<?php

namespace Database\Factories;

use App\Models\MomoSchedule;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MomoScheduleCustomer>
 */
class MomoScheduleCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "momo_schedule_id"=> FactoryHelper::getRandomModelId(MomoSchedule::class),
            "phone_no"=>fake()->phoneNumber(),
            "amount"=>fake()->numberBetween(1000, 200000)
        ];
    }
}
