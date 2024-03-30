<?php

use Task\Response\Response;

/**
 * Глобальная функция отправки ответа Response
 *
 * @param array $data - данные, которые будут отправлены в теле ответа в JSON формате
 * @param int $code - код ответа
 * @return void
 */
function response(array $data = [], int $code = 200): void {
    $response = new Response();
    $response->set($data, $code);
    $response->send();
}