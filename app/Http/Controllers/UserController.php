<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\FailAuth;
use App\Http\Responses\Success;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Метод отвечает за получение информации о пользователе
     *
     * @param int $userId
     * @return Success|FailAuth
     */
    public function index(int $userId): Success|FailAuth
    {
        $userServices = new UserServices();
        $user = Auth::user();

        if ($user->id !== $userId) {
            return new FailAuth();
        }

        $userInfo = $userServices->getUserInfo($userId);

        return new Success(data: $userInfo);
    }

    /**
     * Метод отвечает за обновление информации о пользователе
     *
     * @param UserRequest $request
     * @param int $userId
     * @return Success
     */
    public function update(UserRequest $request, int $userId): Success
    {
        $userServices = new UserServices();
        $user = Auth::user();

        $updatedUser = $userServices->updateUser($request, $user);

        return new Success(data: [
            'updatedUser' => $updatedUser
        ]);
    }
}
