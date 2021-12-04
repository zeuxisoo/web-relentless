<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodMenuService {

    public function create(array $data) {
        $foodMenu = DB::transaction(function() use ($data) {
            // Food menu
            $foodMenu = FoodMenu::create([
                'start_at' => $data['start_at'],
                'remark'   => $data['remark'],
            ]);

            // Food menu tags
            $foodMenu->attachTags($data['tags'], TagCategory::Food);

            // Food menu items
            $fieldValues = [
                'name' => [],
                'unit' => [],
            ];

            foreach($data['foods'] as $food) {
                if (!in_array($food['name'], $fieldValues['name'])) {
                    $fieldValues['name'][] = $food['name'];
                }

                if (!in_array($food['unit'], $fieldValues['unit'])) {
                    $fieldValues['unit'][] = $food['unit'];
                }
            }

            $foodNameIds = FoodName::getOrInsertFoodFields('name', $fieldValues);
            $foodUnitIds = FoodUnit::getOrInsertFoodFields('unit', $fieldValues);

            $foodItems = [];

            foreach($data['foods'] as $food) {
                array_push($foodItems, [
                    'user_id'      => Auth::id(),
                    'food_menu_id' => $foodMenu->id,
                    'food_name_id' => $foodNameIds[$food['name']],
                    'food_unit_id' => $foodUnitIds[$food['unit']],
                    'quantity'     => $food['quantity'],
                ]);
            }

            FoodMenuItem::insert($foodItems);

            // Load the relationship first
            $foodMenu->load('foodItems.foodName', 'foodItems.foodUnit');

            return $foodMenu;
        });

        return $foodMenu;
    }

    public function find(array $data) {
        return FoodMenu::with('foodItems.foodName', 'foodItems.foodUnit')
            ->where('user_id', Auth::id())
            ->find($data['id']);
    }

}
