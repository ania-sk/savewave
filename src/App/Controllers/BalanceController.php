<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{ValidatorService, CategoryService};
use Framework\TemplateEngine;

class   BalanceController
{
    public function __construct(
        private ValidatorService $validatorService,
        private CategoryService $categoryService,
        private TemplateEngine $view
    ) {}

    public function balance()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        echo $this->view->render("/balance.php", [
            'title' => 'Balance',
            'cssLink' => 'mainPage.css',
            'cssLink2' => 'balance.css',
            'jsLink' => '',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
    }
}
