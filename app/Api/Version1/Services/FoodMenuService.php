<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodMenuService {

    public function __construct(
        public FoodMenuItemService $foodMenuItemService
    ) {}

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
            $this->foodMenuItemService->insert($data['foods'], $foodMenu);

            // Load the food items relationship first after items created
            $foodMenu->foods = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

            return $foodMenu;
        });

        return $foodMenu;
    }

    public function list(int $perPage = 8) {
        return $this->userFoodMenuScope()
            ->with('foods.name', 'foods.unit', 'tags')
            ->paginate($perPage);
    }

    public function find(array $data) {
        $foodMenu      = $this->userFoodMenuScope()->find($data['id']);
        $foodMenuItems = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

        $foodMenu->foods = $foodMenuItems;

        return $foodMenu;
    }

    public function update(array $data) {
        $foodMenu = DB::transaction(function() use ($data) {
            $foodMenu = $this->find($data);

            // Remove foods
            // ------------
            // Create must keep food ids
            $mustKeepFoodIds = [];
            foreach($data['foods'] as $food) {
                if (array_key_exists('id', $food)) {
                    array_push($mustKeepFoodIds, $food['id']);
                }
            }

            // Create will remove food ids
            $willRemoveFoodIds = [];
            foreach($foodMenu->foods as $food) {
                if (!in_array($food->id, $mustKeepFoodIds)) {
                    array_push($willRemoveFoodIds, $food->id);
                }
            }

            // Remove food by ids
            if (!empty($willRemoveFoodIds)) {
                $this->foodMenuItemService->deleteByIds($willRemoveFoodIds);
            }

            // Update foods
            // ------------
            // Group the exists foods and request foods by these food ids
            $existsFoods = [];
            foreach($foodMenu->foods as $food) {
                $existsFoods[$food->id] = $food;
            }

            $requestFoods = [];
            foreach($data['foods'] as $food) {
                if (array_key_exists('id', $food)) {
                    $requestFoods[$food['id']] = $food;
                }
            }

            // Group the food name, unit and foods from request foods which should need to update
            $willUpdateFoods = [
                'name' => [],
                'unit' => [],
                'data' => [],
            ];
            foreach($requestFoods as $id => $food) {
                $existsFood = $existsFoods[$id] ?? null;

                if ($existsFood !== null && (
                    $existsFood->name !== $food['name'] ||
                    $existsFood->unit !== $food['unit'] ||
                    $existsFood->quantity != $food['quantity']
                )) {
                    $willUpdateFoods['name'][] = $food['name'];
                    $willUpdateFoods['unit'][] = $food['unit'];
                    $willUpdateFoods['data'][] = $food;
                }
            }

            // Get or create food name, unit ids
            $foodNameIds = FoodName::getOrInsertFoodFields('name', $willUpdateFoods);
            $foodUnitIds = FoodUnit::getOrInsertFoodFields('unit', $willUpdateFoods);

            // Bulk update
            foreach($willUpdateFoods['data'] as $food) {
                $this->foodMenuItemService
                    ->find($food)
                    ->update([
                        'food_name_id' => $foodNameIds[$food['name']],
                        'food_unit_id' => $foodUnitIds[$food['unit']],
                        'quantity'     => $requestFoods[$food['id']]['quantity'],
                    ]);
            }

            // Create foods
            // ------------
            $willInsertFoods = [];
            foreach($data['foods'] as $food) {
                if (!array_key_exists('id', $food)) {
                    array_push($willInsertFoods, $food);
                }
            }

            $this->foodMenuItemService->insert($willInsertFoods, $foodMenu);

            // Update food menu self
            // -------------
            // Unset the customize foods property before update
            unset($foodMenu->foods);

            $foodMenu->update([
                'start_at' => $data['start_at'],
                'remark'   => $data['remark'],
            ]);

            $foodMenu->syncTagsWithType($data['tags'], TagCategory::Food);

            // Reload the food items relationship after items removed, updated and created
            $foodMenu->foods = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

            return $foodMenu;
        });

        return $foodMenu;
    }

    public function search(string $keyword) {
        // TODO: search by keyword in api.food.menu.search
        return [];
    }

    // Shared methods
    protected function userFoodMenuScope() {
        return FoodMenu::where('user_id', Auth::id());
    }

}
