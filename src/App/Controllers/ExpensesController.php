<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\TransactionService;

class ExpensesController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService
    ) {}

    public function expenses()
    {
        $expenses = $this->transactionService->getUserExpenses();

        echo $this->view->render("/expenses.php", [
            'title' => 'Expenses',
            'cssLink' => 'expenses.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'expenses.js',
            'expenses' => $expenses
        ]);
    }
}
