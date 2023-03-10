<?php

use Apex\controllers\AuthController;
use Apex\controllers\SiteController;
use Apex\controllers\TestController;
use Apex\src\Middlewares\AuthMiddleware;
use Apex\src\Router\Router;

Router::get('/', [SiteController::class, 'home']);
Router::delete('/', [SiteController::class, 'home']);
Router::get('/contact', [SiteController::class, 'showContact']);
Router::post('/contact', [SiteController::class, 'storeContact']);

Router::get('/login', [AuthController::class, 'showLogin'])->middleware(new AuthMiddleware(false));
Router::post('/login', [AuthController::class, 'storeLogin'])->middleware(new AuthMiddleware(false));
Router::get('/register', [AuthController::class, 'showRegister'])->middleware(new AuthMiddleware(false));
Router::post('/register', [AuthController::class, 'storeRegister'])->middleware(new AuthMiddleware(false));
Router::post('/logout', [AuthController::class, 'logout'])->middleware(new AuthMiddleware);
Router::get('/profile', [AuthController::class, 'profile'])->middleware(new AuthMiddleware);


Router::get('/test', [TestController::class, 'queryTest'])->middleware(new AuthMiddleware);
Router::get('/test/get', [TestController::class, 'get'])->middleware(new AuthMiddleware(false));
Router::post('/test/post', [TestController::class, 'post'])->middleware(new AuthMiddleware(false));
