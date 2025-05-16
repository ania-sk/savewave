<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;

use App\Controllers\{HomeController, MainPageController, IncomesController};

function registerRoutes(App $app)
{
    $app->get('/', [HomeController::class, 'home']);
    $app->get('/mainPage', [MainPageController::class, 'mainPage']);
    $app->get('/incomes', [IncomesController::class, 'incomes']);
}
