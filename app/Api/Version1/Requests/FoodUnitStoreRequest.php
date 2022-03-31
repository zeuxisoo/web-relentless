<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodUnitStoreRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'required',
                Rule::unique('food_units')->where('user_id', Auth::id())
            ],
        ];
    }

    public function messages(): array {
        return [
            'name.required' => __("Please enter food unit"),
            'name.unique'   => __("Food unit already exists"),
        ];
    }

}
