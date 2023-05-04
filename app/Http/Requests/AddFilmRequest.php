<?php

namespace App\Http\Requests;

use App\Models\Film;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddFilmRequest extends FormRequest
{
    /**
     * Метод проверяет права пользователя для добавления нового фильма в БД
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('store', Film::class);
    }

    /**
     * Правила валидации поля с imdb_id во время добавления нового фильма в БД
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'imdb_id' => [
                'required',
                Rule::unique(Film::class),
                'string',
                'max:20',
                'regex:/(^tt\d{7}$)/'
            ]
        ];
    }
}
