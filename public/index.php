<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Apex\controllers\SiteController;
use Apex\src\App;


$app = new App();
include_once __DIR__ . '/../routes/web.php';
$app->run();