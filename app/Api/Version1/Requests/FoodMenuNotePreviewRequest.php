<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use App\Api\Version1\Rules\FoodParsed;

class FoodMenuNotePreviewRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'text' => ['required', new FoodParsed()],
        ];
    }

    public function messages() {
        return [
            'note.required' => __('Please enter note'),
        ];
    }

}
