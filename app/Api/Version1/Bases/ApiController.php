<?php
namespace App\Api\Version1\Bases;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller {

    protected function guard(): Guard|StatefulGuard {
        return Auth::guard('api');
    }

    protected function currentUser(): mixed {
        return request()->user();
    }

    protected function respondWithMessage(bool $ok, string $message, int $status = 200): JsonResponse {
        return response()->json([
            "ok"   => $ok,
            "data" => [
                "message" => $message,
            ]
        ], $status);
    }

    protected function respondWithSuccess(string $message, int $status = 200): JsonResponse {
        return $this->respondWithMessage(true, $message, $status);
    }

    protected function respondWithFail(string $message, int $status = 200): JsonResponse {
        return $this->respondWithMessage(false, $message, $status);
    }

    protected function respondWithErrors(array $errors, int $status = 422): JsonResponse {
        return response()->json([
            "ok"     => false,
            "errors" => $errors,
        ], $status);
    }

    protected function respondWithData(array $data, int $status = 200): JsonResponse {
        return response()->json([
            'ok'   => true,
            'data' => $data
        ], $status);
    }

}
