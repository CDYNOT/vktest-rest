<?php

namespace Task\Route;

use Task\Request\Request;

/**
 * Маршрутизатор для обработки HTTP запросов и вызова соответствующих методов контроллеров
 */
class Route
{
    /**
     * @var array - Массив маршрутов
     */
    private static array $routes = [];

    /**
     * Регистрация маршрута для запроса методом GET
     *
     * @param string $path - путь маршрута
     * @param array $callback - массив обратного вызова [класс, контроллер, метод]
     * @return void
     */
    public static function get(string $path, array $callback): void
    {
        self::$routes[] = ['path' => $path, 'callback' => $callback, 'method' => 'GET'];
    }

    /**
     * Регистрация маршрута для запроса методом POST
     *
     * @param string $path - путь маршрута
     * @param array $callback - массив обратного вызова [класс, контроллер, метод]
     * @return void
     */
    public static function post(string $path, array $callback): void
    {
        self::$routes[] = ['path' => $path, 'callback' => $callback, 'method' => 'POST'];
    }

    /**
     * Запуск маршрута для обработки текущего запроса
     *
     * @return void
     */
    public static function run(): void
    {
        $request = new Request();
        $method = $request->getMethod();
        $path = $request->getPath();
        foreach (self::$routes as $route) {
            if ($route['path'] === $path && $route['method'] === $method) {
                $callback = $route['callback'];
                $controller = new $callback[0]();
                $method = $callback[1];

                // Передача экземпляра Request в метод контроллера
                $controller->$method($request);
                return;
            }
        }

        // Если маршрут не найден
        response(['error' => 'Method not found'], 404);
    }
}