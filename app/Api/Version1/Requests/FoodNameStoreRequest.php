<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodNameStoreRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'name' => [
                'required',
                Rule::unique("food_names")->where('user_id', Auth::id())
            ],
        ];
    }

    public function messages(): array {
        return [
            'name.required' => __("Please enter food name"),
            'name.unique'   => __("Food name already exists"),
        ];
    }

}
