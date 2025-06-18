<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, CategoryService};

class IncomesController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private CategoryService $categoryService
    ) {}

    public function incomes()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserExpenseCategories($userId);

        $incomes = $this->transactionService->getUserIncomes();

        $incomeToEdit = $_SESSION['incomeToEdit'] ?? null;

        echo $this->view->render("/incomes.php", [
            'title' => 'Incomes',
            'cssLink' => 'incomes.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'incomes.js',
            'incomes' => $incomes,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'incomeToEdit'            => $incomeToEdit
        ]);
    }

    public function editIncome(array $params): void
    {
        $incomeToEdit = $this->transactionService->getUserIncome($params['income']);

        if (!$incomeToEdit) {
            redirectTo('/incomes');
        }

        $_SESSION['activeForm'] = 'editIncome';
        $_SESSION['incomeToEdit'] = $incomeToEdit;

        redirectTo('/incomes');
    }
}
