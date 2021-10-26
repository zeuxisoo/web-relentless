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

    protected function respondWithOK(string $message, int $status = 200) {
        return response()->json([
            "ok"   => true,
            "data" => [
                "message" => $message
            ]
        ], $status);
    }

    protected function respondWithError(string $message, int $status = 200) {
        return response()->json([
            "ok"   => false,
            "data" => [
                "message" => $message,
            ]
        ], $status);
    }

    protected function respondWithJson(array $data, int $status = 200) {
        return response()->json([
            'ok'   => true,
            'data' => $data
        ], $status);
    }

}
