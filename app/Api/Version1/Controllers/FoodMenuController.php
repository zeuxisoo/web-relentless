<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Enums\TagCategory;
use App\Api\Version1\Requests\FoodMenuShowRequest;
use App\Api\Version1\Requests\FoodMenuStoreRequest;
use App\Api\Version1\Transformers\FoodMenuTransformer;
use App\Models\FoodMenu;
use App\Models\FoodMenuItem;
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
            $foodItems = [];

            foreach($input['foods'] as $food) {
                array_push($foodItems, [
                    'user_id'      => Auth::id(),
                    'food_menu_id' => $foodMenu->id,
                    'food_name_id' => $food['food_name_id'],
                    'food_unit_id' => $food['food_unit_id'],
                    'quantity'     => $food['quantity'],
                ]);
            }

            FoodMenuItem::insert($foodItems);

            return $foodMenu;
        });

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function show(FoodMenuShowRequest $id) {
        $foodMenu = FoodMenu::find($id);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

}
