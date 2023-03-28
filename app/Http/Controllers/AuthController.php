<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use app\Http\Responses\Success;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Метод отвечает за вход пользователя на ресурс
     *
     * @param LoginRequest $request
     * @return Success
     */
    public function login(LoginRequest $request): Success
    {
        if (!Auth::attempt($request->validated())) {
            abort(Response::HTTP_UNAUTHORIZED, trans('auth.failed'));
        }

        $token = Auth::user()->createToken('auth-token');

        $data = ['token' => $token->plainTextToken];

        return new Success($data);
    }

    /**
     * Метод отвечает за выход пользователя из ресурса
     *
     * @return Success
     */
    public function logout(): Success
    {
        Auth::user()->tokens()->delete();
        return new Success(null, Response::HTTP_NO_CONTENT);
    }
}
