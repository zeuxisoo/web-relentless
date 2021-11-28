<?php
namespace App\Api\Version1\Requests;

use App\Api\Version1\Bases\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FoodMenuShowRequest extends ApiRequest {

    public function authorize() {
        return true;
    }

    public function all($keys = null) {
        return array_merge(parent::all(), $this->route()->parameters());
    }

    public function rules() {
        return [
            'id'   => [
                'required',
                Rule::exists('food_menus', 'id')->where('user_id', Auth::id()),
            ]
        ];
    }

    public function messages() {
        return [
            'id.required'   => __("Please enter food menu id"),
            'id.exists'     => __("Food menu id is not exists"),
        ];
    }

}
