<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserServices
{
    /**
     * Метод отвечает за получение информации о пользователе
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserInfo(int $userId): Collection
    {
        return User::query()->select(['id', 'name', 'email', 'avatar', 'role'])
            ->whereIn('id', '=', $userId)
            ->get();
    }

    /**
     * Метод отвечает за обновление профиля пользователя
     *
     * @param UserRequest $request
     * @param User $user
     * @return User
     */
    public function updateUser(UserRequest $request, User $user): User
    {
        $params = $request->toArray();

        if (isset($params['password'])) {
            $user->password = Hash::make($params['password']);
        }

        if (isset($params['email'])) {
            $user->email = $params['email'];
        }

        if (isset($params['name'])) {
            $user->name = $params['name'];
        }

        if ($request->hasFile('avatar_url')) {
            $newAvatar = $request->file('avatar_url');
            $oldAvatar = $user->avatar_url;
            if ($oldAvatar) {
                Storage::delete($oldAvatar);
            }
            $filename = $newAvatar->store('public/avatars', 'local');
            $user['avatar_url'] = $filename;
        }


        $user->update();

        return $user;
    }
}
