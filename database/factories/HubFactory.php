<?php

namespace Database\Factories;

use App\Models\Hub;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hub>
 */
class HubFactory extends Factory
{

    protected $model = Hub::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $number = 2; // Initialize a static variable to keep track of the number
        $latitude = fake()->latitude($min = -90, $max = 90);
        $longitude = fake()->longitude($min = -180, $max = 180);

        return [
            'organization_id' => 1,
            'hub_type_id' => 1,
            'subdistrict_id' => 1,
            'code' => 'HUB'.str_pad($number++, 3, '0', STR_PAD_LEFT),
            'name' => fake()->unique()->city(),
            'street_name' => fake()->streetAddress(),
            'street_number' => fake()->buildingNumber(),
            'neighbourhood' => fake()->streetName(),
            'postcode' => fake()->postcode(),
            'maps_url' => "https://www.google.com/maps?q=".$latitude.",".$longitude,
            'coordinate' => $latitude.','.$longitude,
            'is_active' => 1,
            'created_date' => now(),
            'modified_date' => now(),
            'created_by' => 'system',
            'modified_by' => 'system'
        ];
    }
}
