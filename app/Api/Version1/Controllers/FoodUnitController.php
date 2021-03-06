<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodUnitSearchRequest;
use App\Api\Version1\Requests\FoodUnitShowRequest;
use App\Api\Version1\Requests\FoodUnitStoreRequest;
use App\Api\Version1\Requests\FoodUnitUpdateRequest;
use App\Api\Version1\Services\FoodUnitService;
use App\Api\Version1\Transformers\FoodUnitTransformer;
use Spatie\Fractal\Fractal;

class FoodUnitController extends ApiController {

    public function __construct(
        public FoodUnitService $foodUnitService
    ) {}

    public function store(FoodUnitStoreRequest $request): Fractal {
        $input    = $request->only('name');
        $foodUnit = $this->foodUnitService->create($input);

        return fractal($foodUnit, new FoodUnitTransformer());
    }

    public function list() {
        $foodUnits = $this->foodUnitService->list();

        return fractal($foodUnits, new FoodUnitTransformer());
    }

    public function show(FoodUnitShowRequest $request): Fractal {
        $input    = $request->only('id');
        $foodName = $this->foodUnitService->find($input);

        return fractal($foodName, new FoodUnitTransformer());
    }

    public function update(FoodUnitUpdateRequest $request): Fractal {
        $input    = $request->only('id', 'name');
        $foodUnit = $this->foodUnitService->update($input);

        return fractal($foodUnit, new FoodUnitTransformer());
    }

    public function search(FoodUnitSearchRequest $request): Fractal {
        $input     = $request->only('keyword');
        $foodUnits = $this->foodUnitService->search($input['keyword']);

        return fractal($foodUnits, new FoodUnitTransformer());
    }

}
