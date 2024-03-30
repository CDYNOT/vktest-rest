<?php

namespace Task\Controller;

use Task\Model\User;
use Task\Request\Request;

/**
 * Контроллер обработки запроса авторизации пользователя
 */
class AuthorizeController extends Controller
{
    /**
     * @var User - Модель данных для работы с таблицей users
     */
    private User $users;

    public function __construct()
    {
        parent::__construct();

        // Инициализируем модель данных для работы с таблицей users
        $this->users = new User();
    }

    /**
     * Реализация API метода авторизации пользователя.
     * Возвращает access_token, если пользователь существует и пароль совпадает с хэшем,
     * либо возвращает ошибки
     *
     * @param Request $request - объект запроса
     * @return void
     */
    public function authorize(Request $request): void
    {
        $email = $request->get('email');
        $password = $request->get('password');

        // Валидация email и пароля
        $this->validateInputs($email, $password);

        // Ищем в базе пользователя
        $user = $this->users->getUserByEmail($email);

        // Проверка, что пользователь с таким email существует
        $this->checkExistingUser($user);

        // Проверка на соответствие переданного пароля хэшу и пароля базы данных
        $this->passwordHashVerify($password, $user['password']);

        // Создает JWT токен
        $accessToken = $this->createAccessToken($user['id']);

        // Если все ок
        response(['access_token' => $accessToken], 200);
    }

    /**
     * Проверка на пустоту
     *
     * @param string $email - email пользователя
     * @param string $password - введенный пароль
     * @return void
     */
    private function validateInputs(string $email = '', string $password = ''): void
    {
        // Если не передан email или пароль
        if (!$email || !$password) {
            response(['error' => 'email_and_password_required'], 400);
        }

        // Если email не валидный
        if (!$this->helper->validateEmail($email)) {
            response(['error' => 'invalid_email'], 400);
        }
    }

    /**
     * Проверка существования пользователя с указанным email
     *
     * @param array $user - данные пользователя
     * @return void
     */
    private function checkExistingUser(array $user): void
    {
        // Если пользователя не существует
        if (!$user) {
            response(['error' => 'user_not_exist',], 400);
        }
    }

    /**
     * Проверка на соответствие переданного пароля переданному хешу
     *
     * @param string $password - пароль пользователя
     * @param string $hash - хэш пароля пользователя
     * @return void
     */
    private function passwordHashVerify(string $password, string $hash): void
    {
        // Если переданный пароль не прошел проверку на соответствие хешу пароля из базы данных
        if(!$this->helper->passwordVerify($password, $hash)) {
            response(['error' => 'wrong_password'], 400);
        }
    }

    /**
     * Создает JWT токен доступа по переданному идентификатору пользователя
     *
     * @param int $userId - идентификатор пользователя
     * @return string
     */
    private function createAccessToken(int $userId): string
    {
        return $this->helper->encodeAccessToken([
            'user_id' => $userId,
            //'exp' => time() + 120 // Время жизни токена (2 мин)
        ]);
    }
}