<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodMenuSearchRequest;
use App\Api\Version1\Requests\FoodMenuShowRequest;
use App\Api\Version1\Requests\FoodMenuStoreRequest;
use App\Api\Version1\Requests\FoodMenuUpdateRequest;
use App\Api\Version1\Services\FoodMenuService;
use App\Api\Version1\Supports\Fractal;
use App\Api\Version1\Transformers\FoodMenuTransformer;

class FoodMenuController extends ApiController {

    public function __construct(
        public FoodMenuService $foodMenuService
    ) {}

    public function store(FoodMenuStoreRequest $request): Fractal {
        $input    = $request->only('start_at', 'foods', 'tags', 'remark');
        $foodMenu = $this->foodMenuService->create($input);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function list() {
        $foodMenus = $this->foodMenuService->list();

        return fractal($foodMenus, new FoodMenuTransformer());
    }

    public function show(FoodMenuShowRequest $request): Fractal {
        $input    = $request->only('id');
        $foodMenu = $this->foodMenuService->find($input);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function update(FoodMenuUpdateRequest $request): Fractal {
        $input    = $request->only('id', 'start_at', 'foods', 'tags', 'remark');
        $foodMenu = $this->foodMenuService->update($input);

        return fractal($foodMenu, new FoodMenuTransformer());
    }

    public function search(FoodMenuSearchRequest $request): Fractal {
        $input     = $request->only('keyword');
        $foodMenus = $this->foodMenuService->search($input['keyword']);

        return fractal($foodMenus, new FoodMenuTransformer());
    }

}
