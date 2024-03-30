<?php

namespace Task\Controller;

use Task\Request\Request;

/**
 * Контроллер обработки запроса проверки access_token
 */
class FeedController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Реализация API метода проверки access_token.
     * Возвращает access_token, если пользователь существует и пароль совпадает с хэшем,
     * либо возвращает ошибки
     *
     * @param Request $request - объект запроса
     * @return void
     */
    public function feed(Request $request): void
    {
        $token = $request->getHeaderAuthorizationToken();

        // Проверяем передан ли вообще токен
        $this->issetAccessToken($token);

        // Проверяем токен на валидность
        $this->validateToken($token);

        // Если все ок
        response();
    }

    /**
     * Проверка на существование токена в заголовке запроса
     *
     * @param string $token - JWT токен
     * @return void
     */
    private function issetAccessToken(string $token = ''): void
    {
        if (!$token) {
            response([], 401);
        }
    }

    /**
     * Проверка токена на валидность
     *
     * @param string $token - JWT токен
     * @return void
     */
    private function validateToken(string $token): void
    {
        if (!$this->helper->validateAccessToken($token)) {
            response([], 401);
        }
    }
}