<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, CategoryService, ValidatorService};
use Framework\Exceptions\ValidationException;

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

        $totalExpense = $this->transactionService->getBalance($userId, $startDate, $endDate)['totalExpense'];

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $allExpenses = $this->transactionService->getUserExpensesByDateRange($userId, $dtStart, $dtEnd);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
        } else {
            $allExpenses = $this->transactionService->getUserExpenses($userId);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategory($userId);
        }

        //pagination for the transaction table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $totalRecords = count($allExpenses);
        $totalPages = (int)ceil($totalRecords / $limit);

        $expenses = array_slice($allExpenses, $offset, $limit);

        echo $this->view->render("/expenses.php", [
            'title' => 'Expenses',
            'cssLink' => 'expenses.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'expenses.js',
            'expenses' => $expenses,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'expenseChartLabels' => array_column($sumsByCat, 'category'),
            'expenseChartData' => array_map(fn($r) => (float)$r['total'], $sumsByCat),
            'totalExpense' => $totalExpense,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'offset' => $offset
        ]);
    }

    public function updateExpense()
    {
        $formData = $_POST;
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
        $expenseId = (int)$formData['expenseId'];

        try {
            $this->validatorService->validateExpense($formData);
            $this->transactionService->updateExpense($formData, $expenseId);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $formData;
            $_SESSION['activeForm'] = $formData['form_type'];
            redirectTo($redirectPath);
        }
    }


    public function deleteExpense(array $params)
    {
        $this->transactionService->deleteExpense((int) $params['expense']);

        redirectTo('/expenses');
    }
}
