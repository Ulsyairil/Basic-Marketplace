<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        // return $request->expectsJson() ? null : route('login');
        if ($request->is('api/*')) {
            $authorization = $request->header('Authorization');

            if ($authorization === null) {
                throw new HttpException(401, "Authorization header is missing");
            }

            $checkUser = auth('sanctum')->check();
            $token = explode('Bearer ', $authorization);
            if (!$checkUser) {
                throw new HttpException(401, "Authentication Error - Invalid API Key");
            } else {
                $personalToken = PersonalAccessToken::findToken($token[1]);
                $user = User::query()->where('id', $personalToken->tokenable_id)->first();
                if (!$user) {
                    throw new HttpException(401, "Authentication Error - Credentials Not Found");
                }

                if ($user->email_verified_at === null) {
                    throw new HttpException(401, "Authentication Error - Email Not Verified");
                }
            }
        } else {
            throw new HttpException(401, "Must Login First");
        }
    }
}
