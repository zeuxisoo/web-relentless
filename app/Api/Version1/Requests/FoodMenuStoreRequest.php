<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodMenuStoreRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [

        ];
    }

    public function messages() {
        return [

        ];
    }

}
