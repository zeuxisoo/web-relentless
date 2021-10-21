<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodStoreRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name' => 'required|unique:foods,name',
            'unit' => 'required'
        ];
    }

    public function messages() {
        return [
            'name.required'   => __("Please enter name"),
            'unique.required' => __("Food name already exists"),
            'unit.required'   => __("Please enter unit"),
        ];
    }

}
