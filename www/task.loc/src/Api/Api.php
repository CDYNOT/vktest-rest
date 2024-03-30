<?php

namespace Task\Api;

use Task\Route\Route;

/**
 * Класс точки входа в API, запускающий маршрутизатор
 */
class Api {
    /**
     * Запускает маршрутизатор
     *
     * @return void
     */
    public static function run(): void
    {
        Route::run();
    }
}
