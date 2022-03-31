<?php
namespace App\Api\Version1\Controllers;

use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\AuthLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController {

    public function login(AuthLoginRequest $request): JsonResponse {
        $input = $request->only('account', 'password');

        $user = User::where('username', $input['account'])->first();

        if (!$user || Hash::check($input['password'], $user->password) === false) {
            return $this->respondWithFail(__('Invalid credentials, cannot generate token in this stage'));
        }

        $token = $user->createToken($request->ip());

        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse {
        $this->currentUser()->currentAccessToken()->delete();

        return $this->respondWithSuccess(trans('Successfully logged out'));
    }

    // Helper methods
    protected function respondWithToken(NewAccessToken $token): JsonResponse {
        $data = [
            'accessToken' => $token->plainTextToken,
            'tokenType'   => 'bearer',
            'expiresIn'   => 0,
        ];

        $expiration = config('sanctum.expiration');

        if ($expiration != null) {
            $data['expiresIn'] = $token->accessToken->created_at->addMinutes($expiration)->timestamp;
        }

        return $this->respondWithData($data);
    }

}
