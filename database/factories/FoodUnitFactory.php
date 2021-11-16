<?php
namespace Database\Factories;

use App\Models\FoodUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodUnitFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FoodUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            "name" => $this->faker->name(),
        ];
    }

}
