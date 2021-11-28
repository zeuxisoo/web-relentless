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
            'start_at' => 'required|date_format:"Y-m-d H:i:s"',
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

            'foods.*.quantity' => 'required|integer|gt:0',
        ];
    }

    public function messages() {
        return [
            'start_at.required'             => __('Please enter start at'),
            'start_at.date_format'          => __('Invalid format of field start at'),
            'foods.required'                => __('Please enter foods'),
            'foods.array'                   => __('Invalid format of field foods'),
            'tags.required'                 => __('Please enter tags'),
            'tags.array'                    => __('Invalid form of field tags'),
            'foods.*.food_name_id.required' => __('Please enter food name'),
            'foods.*.food_unit_id.required' => __('Please enter food unit'),
            'foods.*.food_name_id.exists'   => __('Food name is not exists'),
            'foods.*.food_unit_id.exists'   => __('Food unit is not exists'),
            'foods.*.quantity.required'     => __('Please enter food quantity'),
            'foods.*.quantity.integer'      => __('Food quantity must be integer'),
            'foods.*.quantity.gt'           => __('Food quantity must gether than zero'),
        ];
    }

}
