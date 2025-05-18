<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;

use App\Controllers\{HomeController, MainPageController, IncomesController, ExpensesController, AuthController};

function registerRoutes(App $app)
{
    $app->get('/', [HomeController::class, 'home']);
    $app->get('/mainPage', [MainPageController::class, 'mainPage']);
    $app->get('/incomes', [IncomesController::class, 'incomes']);
    $app->get('/expenses', [ExpensesController::class, 'expenses']);
    $app->get('/register', [AuthController::class, 'registerView']);
}
