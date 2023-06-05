<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Responses\NotFoundResponse;
use App\Models\Comment;
use App\Models\Film;
use App\Services\CommentServices;
use App\Services\FilmServices;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentController extends Controller
{
    /**
     * @param Comment $commentModel
     * @param CommentServices $commentServices
     * @param FilmServices $filmServices
     */
    public function __construct(
        private Comment $commentModel,
        private CommentServices $commentServices,
        private FilmServices $filmServices
    ) {
    }

    /**
     * Метод показывает все комментарии к фильму
     *
     * @param int $filmId
     * @return NotFoundResponse|SuccessResponse
     */
    public function index(int $filmId): NotFoundResponse|SuccessResponse
    {
        $film = Film::whereId($filmId)->first();

        if (!$film) {
            return new NotFoundResponse();
        }

        $comments = $this->commentModel->getFilmComment($filmId);

        return new SuccessResponse(data: ['comments' => $comments]);
    }

    /**
     * Метод отвечает за добавление нового комментария
     *
     * @param CommentRequest $request
     * @param int $filmId
     * @return SuccessResponse
     * @throws Throwable
     */
    public function store(CommentRequest $request, int $filmId): SuccessResponse
    {
        $params = $request->validated();
        $newRating = $params['rating'] ?? null;
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $newComment = $this->commentServices->addNewComment($params, $user->id, $filmId);

            if ($newRating) {
                $this->filmServices->updateRating($filmId, $newRating);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['comment' => $newComment]);
    }

    /**
     * Метод отвечает за редактирования комментария
     *
     * @param CommentRequest $request
     * @return SuccessResponse
     * @throws Throwable
     */
    public function update(CommentRequest $request): SuccessResponse
    {
        $currentComment = $request->findComment();

        $params = $request->validated();
        $commentId = $currentComment->id;
        $filmId = $currentComment->film_id;
        $newCommentRating = $params['rating'] ?? null;

        DB::beginTransaction();

        try {
            $updatedComment = $this->commentServices->updateComment($commentId, $params);

            if ($newCommentRating) {
                $this->filmServices->updateRating($filmId, $newCommentRating);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse($updatedComment);
    }

    /**
     * Метод отвечает за удаление комментария
     *
     * @param int $commentId
     * @return NotFoundResponse|SuccessResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(int $commentId): NotFoundResponse|SuccessResponse
    {
        $currentComment = Comment::find($commentId);

        if (!$currentComment) {
            return new NotFoundResponse();
        }

        $this->authorize('delete', $currentComment);

        DB::beginTransaction();

        try {
            $this->commentServices->deleteComment($commentId);
            $this->commentServices->deleteChildComment($commentId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return new SuccessResponse(data: ['Комментарий успешно удален']);
    }
}
