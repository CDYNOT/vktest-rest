<?php

namespace Task\Database;

use PDO;
use PDOException;

/**
 * Класс подключения к базе данных MySQL через PDO
 */
class Database {
    /**
     * @var string - Хост базы данных
     */
    private static string $host = 'mysql';

    /**
     * @var string - Имя базы данных
     */
    private static string $database = 'task';

    /**
     * @var string - Пользователь базы данных
     */
    private static string $username = 'cdynot';

    /**
     * @var string - Пароль к базе данных
     */
    private static string $password = 'secret';

    /**
     * @var PDO - Объект подключения к базе данных
     */
    private static PDO $connection;

    /**
     * Возвращает единственный экземпляр подключения к базе данных
     * или создает новое, если оно не существует
     *
     * @return PDO - объект подключения к БД
     */
    public static function getInstance(): PDO
    {
        if (!isset(self::$connection)) {
            $dsn = 'mysql:host='.self::$host.';dbname='.self::$database;

            try {
                self::$connection = new PDO($dsn, self::$username, self::$password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                //die("Connection failed: " . $e->getMessage());
                response(['error' => 'Connection failed'], 500);
            }
        }

        return self::$connection;
    }
}