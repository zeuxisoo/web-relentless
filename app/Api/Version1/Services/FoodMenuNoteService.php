<?php
namespace App\Api\Version1\Services;

use App\Api\Version1\Enums\TagCategory;
use App\Models\FoodMenu;
use Illuminate\Support\Facades\DB;

class FoodMenuNoteService {

    public function __construct(
        public FoodMenuItemService $foodMenuItemService
    ) {}

    public function create(array $foodMenus): array {
        DB::beginTransaction();

        $menus = [];

        foreach($foodMenus as $foodMenu) {
            // Food menu
            $newMenu = FoodMenu::create([
                'start_at' => sprintf('%s %s', $foodMenu['date'], $foodMenu['time']),
                'remark'   => $foodMenu['remark'],
            ]);

            // Food menu tags
            $newMenu->attachTags($foodMenu['tags'], TagCategory::Food);

            // Food menu items
            $this->foodMenuItemService->insert($foodMenu['foods'], $newMenu);

            // Load the food items relationship first after items created
            $newMenu->foods = $this->foodMenuItemService->getByFoodMenuId($newMenu->id);

            //
            $menus[] = $newMenu;
        }

        DB::commit();

        return $menus;
    }

}
