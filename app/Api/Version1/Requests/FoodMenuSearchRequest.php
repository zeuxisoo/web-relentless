<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodMenuSearchRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'keyword' => 'required',
        ];
    }

    public function messages(): array {
        return [
            'keyword.required' => __("Please enter food menu keyword"),
        ];
    }

}
