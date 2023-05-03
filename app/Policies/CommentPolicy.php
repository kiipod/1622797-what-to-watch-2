<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Метод проверяет может ли пользователь или модератор удалить комментарий
     *
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return ($user->is_moderator === true || $user->id === $comment->user_id);
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
        return ($user->is_moderator === true || $user->id === $comment->user_id);
    }
}
