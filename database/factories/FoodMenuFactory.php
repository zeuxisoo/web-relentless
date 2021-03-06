<?php
namespace Database\Factories;

use App\Models\FoodMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoodMenuFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FoodMenu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            "start_at" => $this->faker->dateTime()->format("YYYY-mm-dd HH:mm:ss"),
            "remark" => "",
        ];
    }

}
