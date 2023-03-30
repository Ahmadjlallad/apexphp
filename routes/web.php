<?php

use Apex\controllers\AuthController;
use Apex\controllers\PostController;
use Apex\controllers\SiteController;
use Apex\controllers\TestController;
use Apex\src\Middlewares\AuthMiddleware;
use Apex\src\Router\Router;

Router::get('/', [SiteController::class, 'home']);
Router::delete('/', [SiteController::class, 'home']);
Router::get('/contact', [SiteController::class, 'showContact']);
Router::post('/contact', [SiteController::class, 'storeContact']);

Router::get('/auth/login', [AuthController::class, 'showLogin'])->middleware(new AuthMiddleware(false));
Router::post('/auth/login', [AuthController::class, 'storeLogin'])->middleware(new AuthMiddleware(false));
Router::get('/auth/register', [AuthController::class, 'showRegister'])->middleware(new AuthMiddleware(false));
Router::post('/auth/register', [AuthController::class, 'storeRegister'])->middleware(new AuthMiddleware(false));
Router::post('/auth/logout', [AuthController::class, 'logout'])->middleware(new AuthMiddleware);
Router::get('/profile', [AuthController::class, 'profile'])->middleware(new AuthMiddleware);


Router::get('/test', [TestController::class, 'queryTest'])->middleware(new AuthMiddleware);
Router::get('/test/get', [TestController::class, 'get'])->middleware(new AuthMiddleware(false));
Router::post('/test/post', [TestController::class, 'post'])->middleware(new AuthMiddleware(false));


Router::get('/post/create', [PostController::class, 'createChooseCategory']);
Router::post('/post/create-options', [PostController::class, 'createFillOptions']);