<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Enums\TagCategory;
use App\Api\Version1\Requests\FoodMenuShowRequest;
use App\Api\Version1\Requests\FoodMenuStoreRequest;
use App\Api\Version1\Transformers\FoodMenuTransformer;
use App\Models\FoodMenu;
use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodMenuController extends ApiController {

    public function store(FoodMenuStoreRequest $request) {
        $input = $request->only('start_at', 'foods', 'tags', 'remark');

        $foodMenu = DB::transaction(function() use ($input) {
            // Food menu
            $foodMenu = FoodMenu::create([
                'start_at' => $input['start_at'],
                'remark'   => $input['remark'],
            ]);

            // Food menu tags
            $foodMenu->attachTags($input['tags'], TagCategory::Food);

            // Food menu items
            $fieldValues = [
                'name' => [],
                'unit' => [],
            ];

            foreach($input['foods'] as $food) {
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

            foreach($input['foods'] as $food) {
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

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function show(FoodMenuShowRequest $id) {
        $foodMenu = FoodMenu::with('foodItems.foodName', 'foodItems.foodUnit')->find($id);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

}
