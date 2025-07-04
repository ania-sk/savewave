<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;

use App\Controllers\{
    HomeController,
    MainPageController,
    IncomesController,
    ExpensesController,
    AuthController,
    TransactionsController,
    CategoryController,
    SettingsController,
    BalanceController
};
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
    $app->get('/', [HomeController::class, 'home']);
    $app->get('/mainPage', [MainPageController::class, 'mainPage'])->add(AuthRequiredMiddleware::class);
    $app->get('/incomes', [IncomesController::class, 'incomes'])->add(AuthRequiredMiddleware::class);
    $app->get('/expenses', [ExpensesController::class, 'expenses'])->add(AuthRequiredMiddleware::class);
    $app->get('/settings', [SettingsController::class, 'settings'])->add(AuthRequiredMiddleware::class);
    $app->get('/balance', [BalanceController::class, 'balance'])->add(AuthRequiredMiddleware::class);

    $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
    $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
    $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
    $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);

    $app->get('/logout', [AuthController::class, 'logout'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/income', [TransactionsController::class, 'addIncome'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/expense', [TransactionsController::class, 'addExpense'])->add(AuthRequiredMiddleware::class);

    $app->post('/mainPage/addIncomeCategory', [CategoryController::class, 'addNewIncomeCategory'])->add(AuthRequiredMiddleware::class);
    $app->post('/mainPage/addExpenseCategory', [CategoryController::class, 'addNewExpenseCategory'])->add(AuthRequiredMiddleware::class);
    $app->post('/settings/addIncomeCategory', [CategoryController::class, 'addNewIncomeCategory'])->add(AuthRequiredMiddleware::class);
    $app->post('/settings/addExpenseCategory', [CategoryController::class, 'addNewExpenseCategory'])->add(AuthRequiredMiddleware::class);

    $app->get('/incomes/{income}', [IncomesController::class, 'editIncome']);
    $app->post('/incomes/{income}', [IncomesController::class, 'updateIncome']);
    $app->delete('/incomes/{income}', [IncomesController::class, 'deleteIncome']);

    $app->get('/expenses/{expense}', [ExpensesController::class, 'editExpense']);
    $app->post('/expenses/{expense}', [ExpensesController::class, 'updateExpense']);
    $app->delete('/expenses/{expense}', [ExpensesController::class, 'deleteExpense']);

    $app->post('/settings/email/update', [SettingsController::class, 'updateEmail']);
    $app->post('/settings/update/username', [SettingsController::class, 'updateUsername']);
    $app->post('/settings/update/password', [SettingsController::class, 'updatePassword']);
    $app->post('/settings/deleteAccount', [SettingsController::class, 'deleteAccount']);

    $app->get('/settings/{category}', [CategoryController::class, 'editCategory']);
    $app->post('/settings/{category}', [CategoryController::class, 'updateCategory']);
    $app->post('/settings/{category}/delete', [CategoryController::class, 'deleteCategory']);
}
