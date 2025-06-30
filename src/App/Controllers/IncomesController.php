<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, CategoryService, ValidatorService};

class IncomesController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private CategoryService $categoryService,
        private ValidatorService $validatorService
    ) {}

    public function incomes()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate  = trim($_GET['end_date']   ?? '');

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $incomes = $this->transactionService->getUserIncomesByDateRange($userId, $dtStart, $dtEnd);
        } else {
            $incomes = $this->transactionService->getUserIncomes($userId);
        }

        $incomeToEdit = $_SESSION['incomeToEdit'] ?? null;

        echo $this->view->render("/incomes.php", [
            'title' => 'Incomes',
            'cssLink' => 'incomes.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'incomes.js',
            'incomes' => $incomes,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'incomeToEdit'            => $incomeToEdit,
            'start_date'       => $startDate,
            'end_date'         => $endDate,
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

    public function updateIncome(array $params)
    {
        $incomeToEdit = $this->transactionService->getUserIncome($params['income']);
        $_SESSION['activeForm'] = 'editIncome';

        if (!$incomeToEdit) {
            redirectTo('/incomes');
        }

        $errors = $this->validatorService->validateIncome($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldFormData'] = $_POST;
            $_SESSION['activeForm'] = 'editIncome';
            redirectTo($_SERVER['HTTP_REFERER']);
        }

        $this->transactionService->updateIncome($_POST, $incomeToEdit['id']);

        unset($_SESSION['activeForm']);
        unset($_SESSION['incomeToEdit']);

        redirectTo($_SERVER['HTTP_REFERER']);
    }


    public function deleteIncome(array $params)
    {
        $this->transactionService->deleteIncome((int) $params['income']);

        redirectTo('/incomes');
    }
}
