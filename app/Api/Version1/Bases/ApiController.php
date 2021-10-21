<?php
namespace App\Api\Version1\Bases;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller {

    protected function guard() {
        return Auth::guard('api');
    }

    protected function respondWithOK($message, $status = 200) {
        return response()->json([
            "ok"   => true,
            "data" => [
                "message" => $message
            ]
        ], $status);
    }

    protected function respondWithError($message, $status = 200) {
        return response()->json([
            "ok"   => false,
            "data" => [
                "message" => $message,
            ]
        ], $status);
    }

    protected function respondWithJson($data, $status = 200) {
        return response()->json([
            'ok'   => true,
            'data' => $data
        ], $status);
    }

}
