<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodNameShowRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function all($keys = null): array {
        return array_merge(parent::all(), $this->route()->parameters());
    }

    public function rules(): array {
        return [
            'id'   => [
                'required',
                Rule::exists('food_names', 'id')->where('user_id', Auth::id()),
            ]
        ];
    }

    public function messages(): array {
        return [
            'id.required'   => __("Please enter food name id"),
            'id.exists'     => __("Food name id is not exists"),
        ];
    }

}
