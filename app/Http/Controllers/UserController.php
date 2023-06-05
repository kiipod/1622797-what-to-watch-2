<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Responses\FailAuthResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @param UserServices $userServices
     */
    public function __construct(private UserServices $userServices)
    {
    }

    /**
     * Метод отвечает за получение информации о пользователе
     *
     * @param int $userId
     * @return SuccessResponse|FailAuthResponse
     */
    public function index(int $userId): SuccessResponse|FailAuthResponse
    {
        $user = Auth::user();

        if ($user->id !== $userId) {
            return new FailAuthResponse();
        }

        $userInfo = $this->userServices->getUserInfo($userId);

        return new SuccessResponse(data: $userInfo);
    }

    /**
     * Метод отвечает за обновление информации о пользователе
     *
     * @param UserRequest $request
     * @return SuccessResponse
     */
    public function update(UserRequest $request): SuccessResponse
    {
        $user = Auth::user();

        $updatedUser = $this->userServices->updateUser($request, $user);

        return new SuccessResponse(data: [
            'updatedUser' => $updatedUser
        ]);
    }
}
