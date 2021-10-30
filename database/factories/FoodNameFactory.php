<?php
namespace Database\Factories;

use App\Models\FoodName;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodNameFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FoodName::class;

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
