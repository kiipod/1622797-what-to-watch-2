<?php

namespace App\Services;

use App\Models\Comment;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Illuminate\Database\Eloquent\Model;

class CommentServices
{
    /**
     * Метод отвечает за создание нового комментария
     *
     * @param array $params
     * @param int $userId
     * @param int $filmId
     * @return Comment
     * @throws InternalErrorException
     */
    public function addNewComment(array $params, int $userId, int $filmId): Comment
    {
        $comment = new Comment();

        $comment->text = $params['text'];
        $comment->rating = $params['rating'] ?? null;
        $comment->film_id = $filmId;
        $comment->user_id = $userId;
        $comment->parent_id = $params['parent_id'] ?? null;

        if (!$comment->save()) {
            throw new InternalErrorException('Не удалось сохранить комментарий', 500);
        }

        return $comment;
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $id
     * @return void
     */
    public function deleteComment(int $id): void
    {
        $comment = Comment::whereId($id)->firstOrFail();
        $comment->delete();
    }

    /**
     * Метод удаляет всех потомков комментария
     *
     * @param int $commentId
     * @return void
     */
    public function deleteChildComment(int $commentId): void
    {
        $childComments = Comment::where('parent_id', '=', $commentId)->get();

        foreach ($childComments as $comment) {
            $comment->delete();
        }
    }

    /**
     * Метод отвечает за обновление комментария
     *
     * @throws InternalErrorException
     */
    public function updateComment(int $commentId, array $params): Model
    {
        $comment = Comment::whereId($commentId)->firstOrFail();

        $comment->text = $params['text'];
        $newRating = $params['rating'] ?? null;

        if ($newRating) {
            $comment->rating = $newRating;
        }

        if (!$comment->save()) {
            throw new InternalErrorException('Не удалось изменить комментарий', 500);
        }

        return $comment;
    }
}
