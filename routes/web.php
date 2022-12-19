<?php

use Apex\controllers\AuthController;
use Apex\controllers\SiteController;
use Apex\src\Router\Router;

Router::get('/', [SiteController::class, 'home']);
Router::get('/contact', [SiteController::class, 'showContact']);
Router::post('/contact', [SiteController::class, 'storeContact']);

Router::get('/login', [AuthController::class, 'showLogin']);
Router::post('/login', [AuthController::class, 'storeLogin']);
Router::get('/register', [AuthController::class, 'register']);
Router::post('/register', [AuthController::class, 'register']);
