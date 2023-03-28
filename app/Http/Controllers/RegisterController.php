<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\Success;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * @param UserRequest $request
     * @return Success
     */
    public function register(UserRequest $request): Success
    {
        $params = $request->safe()->except('file');
        $user = User::create($params);
        $token = $user->createToken('auth-token');

        $data = [
            'user' => $user,
            'token' => $token->plainTextToken
        ];

        return new Success($data, Response::HTTP_CREATED);
    }
}
