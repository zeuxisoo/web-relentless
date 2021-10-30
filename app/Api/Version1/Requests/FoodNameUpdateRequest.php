<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodNameUpdateRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'id'   => [
                'required',
                Rule::exists('food_names', 'id')->where('user_id', Auth::id()),
            ],
            'name' => [
                'required',
                Rule::unique("food_names")->where('user_id', Auth::id())
            ],
        ];
    }

    public function messages() {
        return [
            'id.required'   => __("Please enter food name id"),
            'id.exists'     => __("Food name id is not exists"),
            'name.required' => __("Please enter food name"),
            'name.unique'   => __("Food name already exists"),
        ];
    }

}
