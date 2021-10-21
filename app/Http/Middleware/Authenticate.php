<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Stop the redirect when request url start from `api/*`
        // e.g. not redirect to /login when url start from `api/*` and not logged in
        if ($request->is('api/*')) {
            abort(
                response()->json([
                    'ok'   => false,
                    'data' => [
                        'message' => __('Authentication required, Please login first'),
                    ]
                ], 401)
            );
        }

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
