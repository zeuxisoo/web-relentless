<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodNameStoreRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name' => 'required|unique:food_names,name',
        ];
    }

    public function messages() {
        return [
            'name.required' => __("Please enter food name"),
            'name.unique'   => __("Food name already exists"),
        ];
    }

}
