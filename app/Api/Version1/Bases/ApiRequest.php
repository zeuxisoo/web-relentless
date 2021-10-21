<?php
namespace App\Api\Version1\Bases;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest {

    // Override the default validation failed handler,
    // When missing `Accept: application/json` in header, the response json do not redirect back
    protected function failedValidation(Validator $validator) {
        if ($this->container['request'] instanceof Request) {
            throw new HttpResponseException(response()->json([
                'ok'     => false,
                'errors' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }

}
