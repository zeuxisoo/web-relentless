<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use App\Models\FoodName;
use App\Models\FoodUnit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FoodMenuService {

    public function __construct(
        public FoodMenuItemService $foodMenuItemService
    ) {}

    public function create(array $data): Model {
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

    public function list(int $perPage = 8): LengthAwarePaginator {
        return $this->userFoodMenuScope()
            ->with('foods.name', 'foods.unit', 'tags')
            ->paginate($perPage);
    }

    public function find(array $data): Model|Collection|static|null {
        $foodMenu      = $this->userFoodMenuScope()->find($data['id']);
        $foodMenuItems = $this->foodMenuItemService->getByFoodMenuId($foodMenu->id);

        $foodMenu->foods = $foodMenuItems;

        return $foodMenu;
    }

    public function update(array $data): Model|Collection|static|null {
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

    public function search(string $keyword): Collection {
        $foodMenus = [];

        if (env('ENABLE_SCOUT', false)) {
            $foodMenus = FoodMenu::search($keyword)->where('user_id', Auth::id())->get();

            $foodMenus->load('foods.name', 'foods.unit', 'tags');
        }else{
            $results = DB::select(<<<END
                SELECT
                    id,
                    start_at,
                    remark,
                    (
                        SELECT
                            GROUP_CONCAT(JSON_EXTRACT(tags.name, '$.zh_HK'), ',') AS tag_list
                        FROM taggables
                        LEFT JOIN tags
                            ON taggables.tag_id = tags.id
                        WHERE
                            taggables.taggable_id = food_menus.id AND
                            taggables.taggable_type = 'food_menus'
                    ) AS tag_list,
                    (
                        SELECT
                            GROUP_CONCAT(food_names.name || " " || food_menu_items.quantity || " " || food_units.name, ',') AS food_list
                        FROM food_menu_items
                        LEFT JOIN food_names
                            ON food_menu_items.food_name_id = food_names.id
                        LEFT JOIN food_units
                            ON food_menu_items.food_unit_id = food_units.id
                        WHERE
                            food_menu_items.food_menu_id = food_menus.id
                    ) AS food_list
                FROM food_menus
                WHERE
                    user_id = :user_id AND
                    (
                        remark LIKE :keyword OR
                        tag_list LIKE :keyword OR
                        food_list LIKE :keyword
                    )
            END, [
                'user_id' => Auth::id(),
                'keyword' => "%$keyword%",
            ]);

            $foodMenus = FoodMenu::hydrate($results);
            $foodMenus->load('foods.name', 'foods.unit', 'tags');
        }

        return $foodMenus;
    }

    // Shared methods
    protected function userFoodMenuScope(): Builder {
        return FoodMenu::where('user_id', Auth::id());
    }

}
