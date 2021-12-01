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
            $foodNameIds = $this->getOrCreateFieldIdsFromFoods($input['foods'], field: 'name');
            $foodUnitIds = $this->getOrCreateFieldIdsFromFoods($input['foods'], field: 'unit');

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

    //
    protected function getOrCreateFieldIdsFromFoods(array $foods, string $field): array {
        // Select model
        $models = [
            'name' => new FoodName(),
            'unit' => new FoodUnit(),
        ];

        $model = $models[$field];

        // Convert name list from foods by field name
        $names = $this->getFieldNamesFromFoods($foods, $field);

        // Get the exists names by name list
        $existsNames = $model::select('name')
            ->whereIn('name', $names)
            ->where('user_id', Auth::id())
            ->pluck('name')
            ->toArray();

        // Get the not exists names by compare exists names
        $notExistsNames = array_diff($names, $existsNames);

        // Bulk insert the not exists names into database
        if (!empty($notExistsNames)) {
            $notExistsNames = collect($notExistsNames)
                ->map(fn($name) => [
                    'user_id'    => Auth::id(),
                    'name'       => $name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ])
                ->toArray();

            $model::insert($notExistsNames);
        }

        // Re-get the latest exists names and covert to `name: id` structure
        $ids = $model::select('id', 'name')
            ->whereIn('name', $names)
            ->get()
            ->mapWithKeys(fn($row) => [
                $row->name => $row->id
            ])
            ->toArray();

        return $ids;
    }

    protected function getFieldNamesFromFoods(array $foods, string $field): array {
        $names = [];

        foreach($foods as $food) {
            if (!in_array($food[$field], $names)) {
                array_push($names, $food[$field]);
            }
        }

        return $names;
    }

}
