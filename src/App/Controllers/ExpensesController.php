<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, CategoryService, ValidatorService};

class ExpensesController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private CategoryService $categoryService,
        private ValidatorService $validatorService
    ) {}

    public function expenses()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate  = trim($_GET['end_date']   ?? '');

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $expenses = $this->transactionService->getUserExpensesByDateRange($userId, $dtStart, $dtEnd);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
        } else {
            $expenses = $this->transactionService->getUserExpenses($userId);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategory($userId);
        }

        $expenseToEdit = $_SESSION['expenseToEdit'] ?? null;

        echo $this->view->render("/expenses.php", [
            'title' => 'Expenses',
            'cssLink' => 'expenses.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'expenses.js',
            'expenses' => $expenses,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'expenseToEdit' => $expenseToEdit,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'chartLabels' => array_column($sumsByCat, 'category'),
            'chartData' => array_map(fn($r) => (float)$r['total'], $sumsByCat)
        ]);
    }

    public function editExpense(array $params): void
    {
        $expenseToEdit = $this->transactionService->getUserExpense($params['expense']);

        if (!$expenseToEdit) {
            redirectTo('/expenses');
        }

        $_SESSION['activeForm'] = 'editExpense';
        $_SESSION['expenseToEdit'] = $expenseToEdit;

        redirectTo('/expenses');
    }

    public function updateExpense(array $params)
    {
        $expenseToEdit = $this->transactionService->getUserExpense($params['expense']);
        $_SESSION['activeForm'] = 'editExpense';

        if (!$expenseToEdit) {
            redirectTo('/expenses');
        }

        $errors = $this->validatorService->validateExpense($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldFormData'] = $_POST;
            $_SESSION['activeForm'] = 'editExpense';
            redirectTo($_SERVER['HTTP_REFERER']);
        }

        $this->transactionService->updateExpense($_POST, $expenseToEdit['id']);

        unset($_SESSION['activeForm']);
        unset($_SESSION['expenseToEdit']);

        redirectTo($_SERVER['HTTP_REFERER']);
    }


    public function deleteExpense(array $params)
    {
        $this->transactionService->deleteExpense((int) $params['expense']);

        redirectTo('/expenses');
    }
}
