<?php
namespace App\Api\Version1\Bases;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller {

    protected function guard() {
        return Auth::guard('api');
    }

    protected function currentUser() {
        return request()->user();
    }

    protected function respondWithMessage(bool $ok, string $message, int $status = 200) {
        return response()->json([
            "ok"   => $ok,
            "data" => [
                "message" => $message,
            ]
        ], $status);
    }

    protected function respondWithSuccess(string $message, int $status = 200) {
        return $this->respondWithMessage(true, $message, $status);
    }

    protected function respondWithFail(string $message, int $status = 200) {
        return $this->respondWithMessage(false, $message, $status);
    }

    protected function respondWithErrors(array $errors, int $status = 422) {
        return response()->json([
            "ok"     => false,
            "errors" => $errors,
        ], $status);
    }

    protected function respondWithData(array $data, int $status = 200) {
        return response()->json([
            'ok'   => true,
            'data' => $data
        ], $status);
    }

}
