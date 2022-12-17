<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Apex\controllers\SiteController;
use Apex\src\App;


$app = new App();
//$app->router->get('/', fn() => 'hallo word');
$app->router->get('/', 'home');
//$app->router->get('/contact', fn() => 'contact');
$app->router->get('/contact', [SiteController::class, 'view']);
$app->router->post('/contact', [SiteController::class, 'store']);
$app->run();