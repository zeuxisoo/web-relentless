<?php
namespace App\Api\Version1\Services;

use App\Models\FoodMenu;
use App\Models\FoodMenuItem;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FoodMenuItemService {

    public function list(int $perPage = 8) {
        return $this->userFoodMenuItemScope()->paginate($perPage);
    }

    public function find(array $data) {
        return $this->userFoodMenuItemScope()->find($data['id']);
    }

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
                'created_at'   => Carbon::now(),
            ]);
        }

        return FoodMenuItem::insert($foodItems);
    }

    public function getByFoodMenuId(int $id) {
        // return DB::select("
        //     SELECT
        //         food_menu_items.id AS id,
        //         food_menu_items.quantity AS quantity,
        //         food_names.name AS name,
        //         food_units.name AS unit
        //     FROM food_menu_items
        //     LEFT JOIN food_names
        //         ON food_names.id = food_menu_items.food_name_id
        //     LEFT JOIN food_units
        //         ON food_units.id = food_menu_items.food_unit_id
        //     WHERE
        //         food_menu_items.food_menu_id = ?
        //         AND
        //         food_menu_items.user_id = ?
        // ", [
        //     $id,
        //     Auth::id()
        // ]);

        return FoodMenuItem::selectRaw("
                food_menu_items.id AS id,
                food_menu_items.quantity AS quantity,
                food_names.name AS name,
                food_units.name AS unit
            ")
            ->leftJoin('food_names', 'food_names.id', '=', 'food_menu_items.food_name_id')
            ->leftJoin('food_units', 'food_units.id', '=', 'food_menu_items.food_unit_id')
            ->where('food_menu_items.food_menu_id', $id)
            ->where('food_menu_items.user_id', Auth::id())
            ->get();
    }

    public function deleteByIds(array $ids) {
        return $this->userFoodMenuItemScope()
            ->whereIn('id', $ids)
            ->delete();
    }

    // Shared methods
    protected function userFoodMenuItemScope() {
        return FoodMenuItem::where('user_id', Auth::id());
    }

}
