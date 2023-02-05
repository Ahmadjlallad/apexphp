<?php

use Apex\controllers\AuthController;
use Apex\controllers\SiteController;
use Apex\controllers\TestController;
use Apex\src\Middlewares\AuthMiddleware;
use Apex\src\Router\Router;

Router::get('/', [SiteController::class, 'home']);
Router::get('/contact', [SiteController::class, 'showContact']);
Router::post('/contact', [SiteController::class, 'storeContact']);

Router::get('/login', [AuthController::class, 'showLogin']);
Router::post('/s-login', [AuthController::class, 'storeLogin']);
Router::get('/register', [AuthController::class, 'register']);
Router::post('/register', [AuthController::class, 'register']);
Router::post('/logout', [AuthController::class, 'logout']);
Router::get('/profile', [AuthController::class, 'profile']);


Router::get('/test', [TestController::class, 'queryTest'])->middleware(new AuthMiddleware);
