<?php

namespace Task\Controller;

use Task\Model\User;
use Task\Request\Request;

/**
 * Контроллер обработки запроса регистрации нового пользователя
 */
class RegisterController extends Controller
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
     * Реализация API метода регистрации пользователя.
     * Регистрирует нового пользователя и отправляет ответ клиенту,
     * либо возвращает ошибки
     *
     * @param Request $request - объект запроса
     * @return void
     */
    public function register(Request $request): void
    {
        $email = $request->get('email');
        $password = $request->get('password');

        // Валидация email и пароля
        $this->validateInputs($email, $password);

        // Проверка на существование пользователя с таким Email
        $this->checkExistingUser($email);

        // Получаем надежность пароля
        $passwordStrength = $this->helper->validatePasswordStrength($password);

        // Проверка пароля на надежность
        $this->validatePassword($passwordStrength);

        // Создаем нового пользователя и возвращаем его id
        $createdUserId = $this->createUser($email, $password);

        // Проверка был ли создан пользователь
        $this->checkCreatedUserId($createdUserId);

        // Если все ок, отправляем ответ
        response([
            'user_id' => $createdUserId,
            'password_check_status' => $passwordStrength,
        ], 201);
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
     * @param string $email - email пользователя
     * @return void
     */
    private function checkExistingUser(string $email): void
    {
        // Если пользователь существует
        if ($this->users->checkUserExistByEmail($email)) {
            response(['error' => 'user_exist'], 400);
        }
    }

    /**
     * Проверка пароля на надежность
     *
     * @param string|false $passwordCheck - статус надежности пароля (good, perfect или false)
     * @return void
     */
    private function validatePassword(string|false $passwordCheck): void
    {
        // Если не надежный
        if (!$passwordCheck) {
            response(['error' => 'weak_password',], 400);
        }
    }

    /**
     * Создание нового пользователя
     *
     * @param string $email - email нового пользователя
     * @param string $password - пароль нового пользователя
     * @return int - id созданного пользователя или 0 в случае ошибки
     */
    private function createUser(string $email, string $password): int
    {
        return $this->users->createUser($email, $this->helper->passwordHash($password));
    }

    /**
     * Проверка id созданного пользователя
     *
     * @param int $id - идентификатор созданного пользователя или 0
     * @return void
     */
    private function checkCreatedUserId(int $id): void
    {
        // Если по какой-то причине пользователь не был создан
        if (!$id) {
            response(['error' => 'registration_error',], 400);
        }
    }
}