<?php

use Task\Api\Api;
use Task\Controller\AuthorizeController;
use Task\Controller\FeedController;
use Task\Controller\RegisterController;
use Task\Route\Route;

require 'vendor/autoload.php';

// Маршрутизация
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/authorize', [AuthorizeController::class, 'authorize']);
Route::get('/feed', [FeedController::class, 'feed']);

Api::run();