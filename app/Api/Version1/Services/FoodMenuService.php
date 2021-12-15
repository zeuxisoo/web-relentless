<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
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
        $foodMenu = $this->find($data);

        // Create must keep food ids
        $foodIds = [];
        foreach($data['foods'] as $food) {
            if (array_key_exists('id', $food)) {
                array_push($foodIds, $food['id']);
            }
        }

        // Create will remove food ids
        $willRemoveFoodIds = [];
        foreach($foodMenu->foods as $food) {
            if (!in_array($food->id, $foodIds)) {
                array_push($willRemoveFoodIds, $food->id);
            }
        }

        // Remove food by ids
        if (!empty($willRemoveFoodIds)) {
            $this->foodMenuItemService->deleteByIds($willRemoveFoodIds);
        }

        // TODO: Update exists food
    }

    // Shared methods
    protected function userFoodMenuScope() {
        return FoodMenu::where('user_id', Auth::id());
    }

}
