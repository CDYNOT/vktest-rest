<?php

namespace Task\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Класс реализации вспомогательных функций
 */
class Helper
{
    /**
     * @var string - секретный ключ для создания JWT токена
     */
    private string $jwtSecretKey = '123secret321';

    /**
     * Проверяет параметр на соответствие формату Email
     *
     * @param string $email - email
     * @return bool
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Простая функция проверки пароля на надежность
     *
     * @param string $password - проверяемый пароль
     * @return string|false
     */
    public function validatePasswordStrength(string $password): string|false
    {
        $minLength = 8;
        $maxLength = 12;
        $length = strlen($password);

        // Пароль слишком короткий
        if ($length < 8) {
            return false;
        }

        $hasNumber = preg_match('/\d/', $password);
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasLowerCase = preg_match('/[a-z]/', $password);

        // Пароль слишком простой
        if (!$hasNumber || !$hasUpperCase || !$hasLowerCase) {
            return false;
        }

        if ($length >= $maxLength) {
            return 'perfect';
        } elseif ($length >= $minLength) {
            return 'good';
        }

        return false;
    }

    /**
     * Возвращает хеш пароля
     *
     * @param string $password - хэшируемый пароль
     * @return string
     */
    public function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Проверяет, соответствует ли пароль хешу
     *
     * @param string $password - проверяемый пароль
     * @param string $hash - хеш пароля
     * @return bool
     */
    public function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Возвращает JWT токен
     *
     * @param array $payload - кодируемые данные
     * @return string
     */
    public function encodeAccessToken(array $payload): string
    {
        return JWT::encode($payload, $this->jwtSecretKey, 'HS256');
    }

    /**
     * Декодирует и возвращает данные JWT токена в виде массива
     * или пустой массив
     *
     * @param string $jwt - JWT токен
     * @return bool
     */
    public function validateAccessToken(string $jwt): bool
    {
        try {
            return (bool) JWT::decode($jwt, new Key($this->jwtSecretKey, 'HS256'));
        } catch (Exception) {
            return false;
        }
    }
}