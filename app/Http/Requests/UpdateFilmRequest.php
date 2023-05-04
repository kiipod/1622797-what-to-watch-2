<?php

namespace App\Http\Requests;

use App\Models\Film;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFilmRequest extends FormRequest
{
    /**
     * Метод осуществляет поиск фильма по его параметру в роуте
     *
     * @return Film|null
     */
    private function findFilm(): ?Film
    {
        return Film::query()->find($this->route('id'));
    }

    /**
     * Метод проверяет права пользователя для редактирования информации фильма
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $film = $this->findFilm();
        return $this->user()->can('update', $film);
    }

    /**
     * Правила валидации полей при редактировании информации о фильме
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'poster_image' => [
                'string',
                'max:255',
                'regex:/^(img\/)?[a-z0-9]+([\-\.][a-z0-9]+)*\.[a-z]{2,5}$/'
            ],
            'preview_image' => [
                'string',
                'max:255',
                'regex:/^(img\/)?[a-z0-9]+([\-\.][a-z0-9]+)*\.[a-z]{2,5}$/'
            ],
            'background_image' => [
                'string',
                'max:255',
                'regex:/^(img\/)?[a-z0-9]+([\-\.][a-z0-9]+)*\.[a-z]{2,5}$/'
            ],
            'background_color' => [
                'string',
                'max:9',
                'lowercase',
                'regex:/^#[a-z0-9]{6,6}$/'
            ],
            'video_link' => [
                'string',
                'max:255',
                'url'
            ],
            'preview_video_link' => [
                'string',
                'max:255',
                'url'
            ],
            'description' => [
                'string',
                'max:1000'
            ],
            'directors' => [
                'string',
                'max:255',
                'regex:/^[A-Za-zА-Яа-яЁё\s]{2,50}$/u'
            ],
            'actors' => [
                'array'
            ],
            'genre' => [
                'array'
            ],
            'run_time' => [
                'integer'
            ],
            'released' => [
                'integer',
                'between:1895,2033'
            ],
            'imdb_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $rule = Rule::unique(Film::class);
                    $film = $this->findFilm();

                    if ($film?->imdb_id === $value) {
                        return $rule->ignore($film?->id);
                    }

                    return $rule;
                },
                'string',
                'max:20',
                'regex:/(^tt\d{7}$)/'
            ],
            'status' => [
                'required',
                'string'
            ]
        ];
    }
}
