<?php

namespace App\Http\Middleware;

use App\Http\Responses\FailAuthResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            abort(Response::HTTP_UNAUTHORIZED, trans('auth.failed'));
        }

        return null;
    }
}
