<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;

use App\Controllers\{HomeController, MainPageController, IncomesController, ExpensesController, AuthController, TransactionsController, CategoryController};
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
    $app->get('/', [HomeController::class, 'home']);
    $app->get('/mainPage', [MainPageController::class, 'mainPage'])->add(AuthRequiredMiddleware::class);
    $app->get('/incomes', [IncomesController::class, 'incomes']);
    $app->get('/expenses', [ExpensesController::class, 'expenses']);
    $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
    $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
    $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
    $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/income', [TransactionsController::class, 'addIncome'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/expense', [TransactionsController::class, 'addExpense'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/addCategory', [CategoryController::class, 'addNewCategory'])->add(AuthRequiredMiddleware::class);
}
