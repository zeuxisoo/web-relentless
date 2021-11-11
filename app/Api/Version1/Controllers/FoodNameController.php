<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodNameSearchRequest;
use App\Api\Version1\Requests\FoodNameStoreRequest;
use App\Api\Version1\Requests\FoodNameUpdateRequest;
use App\Api\Version1\Services\FoodNameService;
use App\Api\Version1\Transformers\FoodNameTransformer;

class FoodNameController extends ApiController {

    private FoodNameService $foodNameService;

    public function __construct(FoodNameService $foodNameService) {
        $this->foodNameService = $foodNameService;
    }

    public function store(FoodNameStoreRequest $request) {
        $input    = $request->only('name');
        $foodName = $this->foodNameService->create($input);

        return fractal($foodName, new FoodNameTransformer());
    }

    public function update(FoodNameUpdateRequest $request) {
        $input    = $request->only('id', 'name');
        $foodName = $this->foodNameService->update($input);

        return fractal($foodName, new FoodNameTransformer());
    }

    public function list() {
        $foodNames = $this->foodNameService->list();

        return fractal($foodNames, new FoodNameTransformer());
    }

    public function search(FoodNameSearchRequest $request) {
        $input     = $request->only('keyword');
        $foodNames = $this->foodNameService->search($input['keyword']);

        return fractal($foodNames, new FoodNameTransformer());
    }

}
