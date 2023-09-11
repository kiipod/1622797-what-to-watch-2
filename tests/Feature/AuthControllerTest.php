<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест на регистрацию пользователя, если email в базе существует
     *
     * @return void
     */
    public function test_register_with_email_exists()
    {
        $user = User::factory()->create();

        $response = $this->postJson(
            route('register.index'),
            ['name' => 'Vanya Pipkin', 'email' => $user->email, 'password' => '12345678',
            'password_confirmation' => '12345678']
        );

        $response->assertUnprocessable();
    }

    /**
     * Тест на регистрацию нового пользователя
     *
     * @return void
     */
    public function test_register_new_user()
    {
        Storage::fake('local');

        $data = [
            'name' => 'Vanya Pipkin',
            'email' => 'email@mail.ru',
            'password' => 'password',
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ];

        $response = $this->postJson(route('register.index'), $data);

        $response->assertOk();

        $newUser = User::whereEmail($data['email'])->first();
        $avatar = $newUser->avatar;

        $this->assertNotEmpty($newUser);
        $this->assertNotEmpty($avatar);
    }

    /**
     * Тест на регистрацию нового пользователя, если была допущена ошибка при вводе данных
     *
     * @return void
     */
    public function test_register_with_validation_error()
    {
        $user = User::factory()->create();

        $response = $this->postJson(
            route('register.index'),
            ['name' => 'Vanya Pipkin', 'email' => $user->email,
            'password' => ' '
            ]
        );
        $response->assertUnprocessable();
    }

    /**
     * Тест на авторизацию пользователя
     *
     * @return void
     */
    public function test_login_user()
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'email@email.ru',
            'password' => 'password'
        ]);

        $response->assertOk();
    }

    /**
     * Тест на авторизацию пользователя, если была допущена ошибка
     *
     * @return void
     */
    public function test_login_user_with_validation_error()
    {
        $response = $this->postJson(route('auth.login'), ['email' => 'not email',
            'password' => '12345678000']);

        $response->assertStatus(500);
    }

    /**
     * Тест логаута, если пользователь не авторизован на сайте
     *
     * @return void
     */
    public function test_logout_no_auth_user()
    {
        $response = $this->postJson(route('auth.logout'));
        $response->assertUnauthorized();
    }

    /**
     * Тест логаута, если пользователь авторизован на сайте
     *
     * @return void
     */
    public function test_logout_auth_user()
    {
        $this->postJson(route('register.index'), ['name' => 'Vanya Pipkin',
            'email' => 'email@mail.ru', 'password' => '12345678']);

        $this->postJson(route('auth.login'), ['email' => 'email@mail.ru', 'password' => '12345678']);

        $response = $this->postJson(route('auth.logout'));

        $response->assertOk();
    }
}
