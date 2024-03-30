<?php

namespace Task\Model;

use PDO;
use Task\Database\Database;

/**
 * Абстрактный класс модели для работы с базой данных
 */
abstract class Model {
    /**
     * @var PDO - Объект подключения к базе данных
     */
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance();
    }

    /**
     * Возвращает объект подключения к базе данных
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}