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

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $allIncomes = $this->transactionService->getUserIncomesByDateRange($userId, $dtStart, $dtEnd);
            $sumsByCat = $this->transactionService->getIncomeSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
        } else {
            $allIncomes = $this->transactionService->getUserIncomes($userId);
            $sumsByCat = $this->transactionService->getIncomeSumsByCategory($userId);
        }

        //pagination for the transaction table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $totalRecords = count($allIncomes);
        $totalPages = (int)ceil($totalRecords / $limit);

        $incomes = array_slice($allIncomes, $offset, $limit);

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

        try {
            $this->validatorService->validateIncome($formData);
            $this->transactionService->updateIncome($formData, $incomeId);
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
        $this->transactionService->deleteIncome((int) $params['income']);

        redirectTo('/incomes');
    }
}
