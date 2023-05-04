<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreRequest extends FormRequest
{
    /**
     * Метод проверяет действительно ли пользователь имеет право редактировать жанр
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $genre = $this->route('genre');
        return $genre && $this->user()->can('update', $genre);
    }

    /**
     * Правила валидации изменения названия жанра
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'genre' => [
                'required',
                'string',
                'max:128'
            ],
        ];
    }
}
