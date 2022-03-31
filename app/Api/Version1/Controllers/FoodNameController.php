<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\FoodNameSearchRequest;
use App\Api\Version1\Requests\FoodNameShowRequest;
use App\Api\Version1\Requests\FoodNameStoreRequest;
use App\Api\Version1\Requests\FoodNameUpdateRequest;
use App\Api\Version1\Services\FoodNameService;
use App\Api\Version1\Transformers\FoodNameTransformer;
use Spatie\Fractal\Fractal;

class FoodNameController extends ApiController {

    public function __construct(
        public FoodNameService $foodNameService
    ) {}

    public function store(FoodNameStoreRequest $request): Fractal {
        $input    = $request->only('name');
        $foodName = $this->foodNameService->create($input);

        return fractal($foodName, new FoodNameTransformer());
    }

    public function list(): Fractal {
        $foodNames = $this->foodNameService->list();

        return fractal($foodNames, new FoodNameTransformer());
    }

    public function show(FoodNameShowRequest $request): Fractal {
        $input    = $request->only('id');
        $foodName = $this->foodNameService->find($input);

        return fractal($foodName, new FoodNameTransformer());
    }

    public function update(FoodNameUpdateRequest $request): Fractal {
        $input    = $request->only('id', 'name');
        $foodName = $this->foodNameService->update($input);

        return fractal($foodName, new FoodNameTransformer());
    }

    public function search(FoodNameSearchRequest $request): Fractal {
        $input     = $request->only('keyword');
        $foodNames = $this->foodNameService->search($input['keyword']);

        return fractal($foodNames, new FoodNameTransformer());
    }

}
