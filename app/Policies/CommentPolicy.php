<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Метод проверяет может ли пользователь или модератор удалить комментарий
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        if ($user->userRole->role === User::MODERATOR_ROLE) {
            return true;
        }

        return $user->id === $comment->user_id;
    }

    /**
     * Метод проверяет может ли пользователь или модератор удалить комментарий
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->userRole->role === User::MODERATOR_ROLE) {
            return true;
        }

        return $user->id === $comment->user_id;
    }
}
