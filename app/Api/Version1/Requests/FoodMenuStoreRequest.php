<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodMenuStoreRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'start_at' => 'required|date_format:"Y-m-d H:i:s"|after_or_equal:now',
            'foods'    => 'required|array',
            'tags'     => 'required|array',
            'remark'   => '',

            'foods.*.food_name_id' => [
                'required',
                Rule::exists('food_names', 'id')->where('user_id', Auth::id()),
            ],

            'foods.*.food_unit_id' => [
                'required',
                Rule::exists('food_units', 'id')->where('user_id', Auth::id()),
            ],
        ];
    }

    public function messages() {
        return [
            'start_at.required'             => 'Please enter start at',
            'start_at.date_format'          => 'Invalid format of field start at',
            'foods.required'                => 'Please enter foods',
            'foods.array'                   => 'Invalid format of field foods',
            'tags.required'                 => 'Please enter tags',
            'tags.array'                    => 'Invalid form of field tags',
            'foods.*.food_name_id.required' => 'Please enter food name',
            'foods.*.food_unit_id.required' => 'Please enter food unit',
            'foods.*.food_name_id.exists'   => 'Food name is not exists',
            'foods.*.food_unit_id.exists'   => 'Food unit is not exists',
        ];
    }

}
