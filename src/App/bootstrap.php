<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Controllers\{HomeController, MainPageController};

$app = new App();

$app->get('/', [HomeController::class, 'home']);
$app->get('/mainPage', [MainPageController::class, 'mainPage']);

return $app;
