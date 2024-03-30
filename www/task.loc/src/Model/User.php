<?php

namespace Task\Model;

use PDO;

/**
 * Класс модели для работы с пользователями
 */
class User extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Создает нового пользователя
     *
     * @param string $email - email нового пользователя
     * @param string $hashedPassword - хэш пароля нового пользователя
     * @return int - идентификатор созданного пользователя или 0 в случае ошибки
     */
    public function createUser(string $email, string $hashedPassword): int
    {
        $sql = 'INSERT INTO users (email, password) VALUES (:email, :password)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $hashedPassword);

        if ($statement->execute()) {
            return (int) $this->getConnection()->lastInsertId();
        } else {
            return 0;
        }
    }

    /**
     * Проверяет, существует ли пользователь с данным email
     *
     * @param string $email - email пользователя
     * @return bool
     */
    public function checkUserExistByEmail(string $email): bool
    {
        $sql = 'SELECT 1 FROM users WHERE email = :email LIMIT 1';
        $statement = $this->getConnection()->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Получает информацию о пользователе по email
     *
     * @param string $email - email пользователя
     * @return array - данные пользователя
     */
    public function getUserByEmail(string $email): array
    {
        $sql = 'SELECT * FROM users WHERE email = :email LIMIT 1';
        $statement = $this->getConnection()->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();

        $userData = $statement->fetch(PDO::FETCH_ASSOC);
        return $userData ?: [];
    }
}