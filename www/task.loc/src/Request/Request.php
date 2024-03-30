<?php

namespace Task\Request;

/**
 * Класс запроса к API
 */
class Request
{
    /**
     * @var array - данные из тела запроса после парсинга JSON объекта
     */
    private array $data;

    /**
     * @var string - метод запроса
     */
    private string $method;

    /**
     * @var string - строка запроса
     */
    private string $path;

    public function __construct()
    {
        $this->initData();
        $this->initMethod();
        $this->initPath();
    }

    /**
     * Сохраняем json данные запроса и устанавливаем в массив
     *
     * @return void
     */
    private function initData(): void
    {
        $this->data = json_decode(file_get_contents('php://input'), true) ?: [];
    }

    /**
     * Сохраняем метод запроса
     *
     * @return void
     */
    private function initMethod(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Сохраняем строку запроса
     *
     * @return void
     */
    private function initPath(): void
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Возвращает метод запроса
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Возвращает строку запроса
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Возвращает значение параметра запроса по его имени
     *
     * @param string $name - имя параметра
     * @return mixed
     */
    public function get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Возвращает значение заголовка авторизации
     *
     * @return string
     */
    public function getHeaderAuthorizationToken(): string
    {
        $headers = apache_request_headers();
        $authorization = $headers['Authorization'] ?? '';
        if (str_starts_with($authorization, 'Bearer ')) {
            // Извлекаем токен из строки Authorization
            return trim(substr($authorization, 7));
        }
        return '';
    }
}