<?php

namespace Database\Factories;

use App\Models\UniqueNo;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UniqueNo>
 */
class UniqueNoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $randId = FactoryHelper::getRandomModelId(UniqueNo::class);
        return [
            // "no"=> str_pad("REF",6,$randId, STR_PAD_LEFT),
            "no"=> str_pad(rand(1,50), 6, '0', STR_PAD_LEFT),
        ];
    }
}
