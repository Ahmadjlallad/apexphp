<?php

use Apex\controllers\AuthController;
use Apex\controllers\SiteController;
use Apex\controllers\TestController;
use Apex\src\Middlewares\AuthMiddleware;
use Apex\src\Router\Router;

Router::get('/', [SiteController::class, 'home']);
Router::get('/contact', [SiteController::class, 'showContact']);
Router::post('/contact', [SiteController::class, 'storeContact']);

Router::get('/login', [AuthController::class, 'showLogin'])->middleware(new AuthMiddleware(false));
Router::post('/s-login', [AuthController::class, 'storeLogin'])->middleware(new AuthMiddleware(false));
Router::get('/register', [AuthController::class, 'register'])->middleware(new AuthMiddleware(false));
Router::post('/register', [AuthController::class, 'register'])->middleware(new AuthMiddleware(false));
Router::post('/logout', [AuthController::class, 'logout'])->middleware(new AuthMiddleware);
Router::get('/profile', [AuthController::class, 'profile'])->middleware(new AuthMiddleware);


Router::get('/test', [TestController::class, 'queryTest'])->middleware(new AuthMiddleware);
