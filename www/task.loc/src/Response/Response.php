<?php

namespace Task\Response;

use JetBrains\PhpStorm\NoReturn;

/**
 * Реализация класса для работы с ответами от API
 */
class Response
{
    /**
     * @var int - код для ответа
     */
    private int $code;

    /**
     * @var array - данные для ответа
     */
    private array $data;

    /**
     * Подготавливает ответ перед отправкой
     *
     * @param array $data
     * @param int $code
     * @return void
     */
    public function set(array $data = [], int $code = 200): void
    {
        $this->setCode($code);
        $this->setData($data);
    }

    /**
     * Отправляет ответ на запрос клиенту с телом JSON (если установлено) и кодом
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->code);
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header('Content-Type: application/json');

        if (!empty($this->data)) {
            echo json_encode($this->data);
        }

        exit();
    }

    /**
     * Устанавливает код ответа
     *
     * @param int $code
     * @return void
     */
    private function setCode(int $code = 200): void
    {
        $this->code = $code;
    }

    /**
     * Устанавливает тело ответа
     *
     * @param array $data
     * @return void
     */
    private function setData(array $data = []): void
    {
        $this->data = $data;
    }
}