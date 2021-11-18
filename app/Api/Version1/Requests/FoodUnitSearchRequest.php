<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodUnitSearchRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'keyword' => 'required',
        ];
    }

    public function messages() {
        return [
            'keyword.required' => __("Please enter food unit keyword"),
        ];
    }

}
