<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, CategoryService, ValidatorService};
use Framework\Exceptions\ValidationException;

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

        $totalIncome = $this->transactionService->getBalance($userId, $startDate, $endDate)['totalIncome'];

        //pagination for the transaction table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $incomes = $this->transactionService->getUserIncomesPageByDateRange($userId, $dtStart, $dtEnd, $limit, $offset);
            $totalRecords = $this->transactionService->countUserIncomesByDateRange($userId, $dtStart, $dtEnd);
            $sumsByCat = $this->transactionService->getIncomeSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
        } else {
            $incomes = $this->transactionService->getUserIncomesPage($userId, $limit, $offset);
            $totalRecords = $this->transactionService->countUserIncomes($userId);
            $sumsByCat = $this->transactionService->getIncomeSumsByCategory($userId);
        }

        $totalPages = (int)ceil($totalRecords / $limit);

        echo $this->view->render("/incomes.php", [
            'title' => 'Incomes',
            'cssLink' => 'incomes.css',
            'cssLink2' => 'main.css',
            'jsLink' => 'incomes.js',
            'incomes' => $incomes,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'incomeChartLabels' => array_column($sumsByCat, 'category'),
            'incomeChartData' => array_map(fn($r) => (float)$r['total'], $sumsByCat),
            'totalIncome' => $totalIncome,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'offset' => $offset
        ]);
    }

    public function updateIncome()
    {
        $formData = $_POST;
        $redirectPath = $_POST['redirect_to'] ?? '/homePage';
        $incomeId = (int)$formData['incomeId'];
        $userId = (int)$_SESSION['user'];

        try {
            $this->validatorService->validateIncome($formData);
            $this->transactionService->updateIncome($formData, $incomeId, $userId);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $formData;
            $_SESSION['activeForm'] = $formData['form_type'];
            redirectTo($redirectPath);
        }
    }


    public function deleteIncome(array $params)
    {
        $userId = $_SESSION['user'];
        $incomeId = (int) $params['income'];
        $this->transactionService->deleteIncome($incomeId, $userId);

        redirectTo('/incomes');
    }
}
