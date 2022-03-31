<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class AuthLoginRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'account'  => 'required',
            'password' => 'required'
        ];
    }

    public function messages(): array {
        return [
            'account.required' => __("Please enter account"),
            'password.required' => __("Please enter password"),
        ];
    }

}
