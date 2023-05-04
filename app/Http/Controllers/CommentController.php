<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Responses\FailPageNotFound;
use App\Models\Comment;
use App\Models\Film;
use App\Services\CommentServices;
use App\Services\FilmServices;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Responses\Success;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentController extends Controller
{
    /**
     * Метод показывает все комментарии к фильму
     *
     * @param int $filmId
     * @return FailPageNotFound|Success
     */
    public function index(int $filmId): FailPageNotFound|Success
    {
        $commentClass = new Comment();

        $film = Film::whereId($filmId)->first();

        if (!$film) {
            return new FailPageNotFound();
        }

        $comments = $commentClass->getFilmComment($filmId);

        return new Success(data: ['comments' => $comments]);
    }

    /**
     * Метод отвечает за добавление нового комментария
     *
     * @param CommentRequest $request
     * @param int $filmId
     * @return Success
     * @throws Throwable
     */
    public function store(CommentRequest $request, int $filmId): Success
    {
        $commentService = new CommentServices();
        $filmService = new FilmServices();

        $params = $request->validated();
        $newRating = $params['rating'] ?? null;
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $newComment = $commentService->addNewComment($params, $user->id, $filmId);

            if ($newRating) {
                $filmService->updateRating($filmId, $newRating);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new Success(data: ['comment' => $newComment]);
    }

    /**
     * Метод отвечает за редактирования комментария
     *
     * @param CommentRequest $request
     * @return Success
     * @throws Throwable
     */
    public function update(CommentRequest $request): Success
    {
        $commentService = new CommentServices();
        $filmService = new FilmServices();

        $currentComment = $request->findComment();

        $params = $request->validated();
        $commentId = $currentComment->id;
        $filmId = $currentComment->film_id;
        $newCommentRating = $params['rating'] ?? null;

        DB::beginTransaction();

        try {
            $updatedComment = $commentService->updateComment($commentId, $params);

            if ($newCommentRating) {
                $filmService->updateRating($filmId, $newCommentRating);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new Success($updatedComment);
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $commentId
     * @return FailPageNotFound|Success
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(int $commentId): FailPageNotFound|Success
    {
        $commentService = new CommentServices();

        $currentComment = Comment::find($commentId);

        if (!$currentComment) {
            return new FailPageNotFound();
        }

        $this->authorize('delete', $currentComment);

        DB::beginTransaction();

        try {
            $commentService->deleteComment($commentId);
            $commentService->deleteChildComment($commentId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new Success(data: ['Комментарий успешно удален']);
    }
}
