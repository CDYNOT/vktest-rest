<?php

namespace Task\Controller;

use Task\Helper\Helper;

/**
 * Базовый класс контроллера
 */
class Controller
{
    /**
     * @var Helper - объект класса вспомогательных функций
     */
    protected Helper $helper;

    public function __construct()
    {
        // Инициализация объекта класса вспомогательных функций
        $this->helper = new Helper();
    }
}