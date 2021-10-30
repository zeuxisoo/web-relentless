<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodUnitUpdateRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'id'   => [
                'required',
                Rule::exists('food_units', 'id')->where('user_id', Auth::id()),
            ],
            'name' => [
                'required',
                Rule::unique("food_units")->where('user_id', Auth::id())
            ],
        ];
    }

    public function messages() {
        return [
            'id.required'   => __("Please enter food unit id"),
            'id.exists'     => __("Food unit id is not exists"),
            'name.required' => __("Please enter food unit"),
            'name.unique'   => __("Food unit already exists"),
        ];
    }

}
