<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;

class FoodMenuNotePreviewRequest extends ApiRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'text' => ['required'],
        ];
    }

    public function messages(): array {
        return [
            'note.required' => __('Please enter note'),
        ];
    }

}
