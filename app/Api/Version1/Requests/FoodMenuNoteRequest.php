<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodMenuNoteRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'text' => ['required'],
        ];
    }

    public function messages() {
        return [
            'note.required' => __('Please enter note'),
        ];
    }

}
