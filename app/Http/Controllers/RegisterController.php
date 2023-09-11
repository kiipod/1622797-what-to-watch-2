<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\FailAuthResponse;
use App\Http\Responses\FailResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * @param UserRequest $request
     * @return FailAuthResponse|SuccessResponse
     */
    public function register(UserRequest $request): FailResponse|SuccessResponse
    {
        try {
            $data = $request->safe()->except('avatar_url');
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);
            $token = $user->createToken('auth_token')->plainTextToken;

            return new SuccessResponse(data: [
                    'user' => $user,
                    'token' => $token
                ]);
        } catch (\Exception) {
            return new FailResponse();
        }
    }
}
