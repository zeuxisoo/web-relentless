<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodUnitStoreRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name' => 'required|unique:food_units,name',
        ];
    }

    public function messages() {
        return [
            'name.required' => __("Please enter food unit name"),
            'name.unique'   => __("Food unit name already exists"),
        ];
    }

}
