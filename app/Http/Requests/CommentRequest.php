<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    /**
     * Метод находит нужный комментарий для удаления и редактирования
     *
     * @return Comment|null
     */
    public function findComment(): ?Comment
    {
        return Comment::find($this->route('comment'));
    }

    /**
     * Метод проверяет права пользователя для редактирования и удаления комментария
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->isMethod('patch')) {
            $comment = $this->findComment();
            return $this->user()->can('update', $comment);
        }

        if ($this->isMethod('delete')) {
            $comment = $this->findComment();
            return $this->user()->can('destroy', $comment);
        }

        return true;
    }

    /**
     * Правила валидации полей редактирования комментария
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
                'between:50,400'
            ],
            'rating' => [
                'nullable',
                'integer',
                'between:1,10'
            ],
            'parent_id' => [
                'integer',
                Rule::in(array_map(
                    static fn ($comment) => $comment['id'],
                    Comment::all('id')->toArray()
                ))
            ],
        ];
    }
}
