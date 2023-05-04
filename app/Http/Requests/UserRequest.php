<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UserRequest extends FormRequest
{
    /**
     * Метод определяет авторизован ли пользователь
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации полей при регистрации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $this->getUniqRule(),
            ],
            'password' => [
                $this->getPasswordRequiredRule(),
                'string',
                'min:8',
                'confirmed',
            ],
            'avatar_url' => 'nullable|image|max:10240'
        ];
    }

    /**
     * Метод проверяет e-mail на уникальность
     *
     * @return Unique
     */
    private function getUniqRule(): Unique
    {
        $rule = Rule::unique(User::class);

        if ($this->isMethod('patch') && Auth::check()) {
            return $rule->ignore(Auth::user());
        }

        return $rule;
    }

    /**
     * Метод проверяет пароль
     *
     * @return string
     */
    private function getPasswordRequiredRule(): string
    {
        return $this->isMethod('patch') ? 'sometimes' : 'required';
    }
}
