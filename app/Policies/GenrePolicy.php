<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class GenrePolicy
{
    /**
     * Метод проверяет что пользователь имеет права Модератора для обновления жанра
     *
     * @param User $user
     * @return Response|bool
     */
    public function update(User $user): Response|bool
    {
        return $user->userRole->role === User::MODERATOR_ROLE;
    }
}
