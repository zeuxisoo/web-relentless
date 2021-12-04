<?php
namespace App\Api\Version1\Services;

use App\Models\FoodMenu;
use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Illuminate\Support\Facades\Auth;

class FoodMenuItemService {

    public function insert(array $foods, FoodMenu $foodMenu) {
        /**
         * Group by the field value
         *
         * [
         *     ['name' => 'water', 'unit' => 'cup'],
         *     ['name' => 'cola', 'unit' => 'cup']
         * ]
         * [
         *     'name' => ['water', 'cola'],
         *     'unit' => ['cup]
         * ]
         *
         * $fieldValues = collect($input['foods'])
         *       ->groupBy(fn() => ['name', 'unit'], preserveKeys: true)
         *       ->map(fn($item, $key) => $item->groupBy($key)->keys())
         *       ->toArray();
         */
        $fieldValues = [
            'name' => [],
            'unit' => [],
        ];

        foreach($foods as $food) {
            if (!in_array($food['name'], $fieldValues['name'])) {
                $fieldValues['name'][] = $food['name'];
            }

            if (!in_array($food['unit'], $fieldValues['unit'])) {
                $fieldValues['unit'][] = $food['unit'];
            }
        }

        // Get or Create the field value ids
        $foodNameIds = FoodName::getOrInsertFoodFields('name', $fieldValues);
        $foodUnitIds = FoodUnit::getOrInsertFoodFields('unit', $fieldValues);

        // Create foods
        $foodItems = [];

        foreach($foods as $food) {
            array_push($foodItems, [
                'user_id'      => Auth::id(),
                'food_menu_id' => $foodMenu->id,
                'food_name_id' => $foodNameIds[$food['name']],
                'food_unit_id' => $foodUnitIds[$food['unit']],
                'quantity'     => $food['quantity'],
            ]);
        }

        return FoodMenuItem::insert($foodItems);
    }

}
