<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\FailAuthResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Метод отвечает за вход пользователя на ресурс
     *
     * @param LoginRequest $request
     * @return FailAuthResponse|SuccessResponse
     */
    public function login(LoginRequest $request): FailAuthResponse|SuccessResponse
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return new FailAuthResponse(trans('auth.failed'), Response::HTTP_UNAUTHORIZED);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token');

            return new SuccessResponse(data: [
                'token' => $token->plainTextToken,
                'user' => $user
            ]);
        } catch (\Exception) {
            return new FailAuthResponse();
        }
    }

    /**
     * Метод отвечает за выход пользователя из ресурса
     *
     * @return SuccessResponse|FailResponse
     */
    public function logout(): SuccessResponse|FailResponse
    {
        try {
            Auth::user()->tokens()->delete();
            return new SuccessResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception) {
            return new FailResponse();
        }
    }
}
