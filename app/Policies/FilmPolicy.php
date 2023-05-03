<?php

namespace App\Policies;

use App\Models\User;

class FilmPolicy
{
    /**
     * Метод проверяет что добавлять новый фильм может только модератор
     *
     * @param User $user
     * @return bool
     */
    public function store(User $user): bool
    {
        return $user->is_moderator === true;
    }

    /**
     * Метод проверяет что только модератор может редактировать фильм
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $user->is_moderator === true;
    }
}
