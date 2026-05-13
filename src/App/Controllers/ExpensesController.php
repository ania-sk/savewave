<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{
    TransactionService,
    CategoryService,
    ValidatorService,
    OcrService
};
use Framework\Exceptions\ValidationException;


class ExpensesController
{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private CategoryService $categoryService,
        private ValidatorService $validatorService,
        private OcrService $ocrService
    ) {}

    public function expenses()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate  = trim($_GET['end_date']   ?? '');

        $totalExpense = $this->transactionService->getBalance($userId, $startDate, $endDate)['totalExpense'];

        //pagination for the transaction table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        if ($startDate !== '' && $endDate !== '') {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $expenses = $this->transactionService->getUserExpensesPageByDateRange($userId, $dtStart, $dtEnd, $limit, $offset);
            $totalRecords = $this->transactionService->countUserExpensesByDateRange($userId, $dtStart, $dtEnd);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
        } else {
            $expenses = $this->transactionService->getUserExpensesPage($userId, $limit, $offset);
            $totalRecords = $this->transactionService->countUserExpenses($userId);
            $sumsByCat = $this->transactionService->getExpenseSumsByCategory($userId);
        }

        $totalPages = (int)ceil($totalRecords / $limit);

        echo $this->view->render("/expenses.php", [
            'title' => 'Expenses',
            'cssLink' => 'expenses.css',
            'cssLink2' => 'main.css',
            'jsLink' => 'expenses.js',
            'jsLink2' => 'ocr.js',
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
        $redirectPath = $_POST['redirect_to'] ?? '/homePage';
        $expenseId = (int)$formData['expenseId'];
        $userId = $_SESSION['user'];

        try {
            $this->validatorService->validateExpense($formData);
            $this->transactionService->updateExpense($formData, $expenseId, $userId);
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
        $userId = $_SESSION['user'];
        $expenseId = (int) $params['expense'];
        $this->transactionService->deleteExpense($expenseId, $userId);

        redirectTo('/expenses');
    }

    public function uploadReceipt()
    {
        $userId = (int)$_SESSION['user'] ?? 0;
        $file = $_FILES['receipt'] ?? null;

        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Błąd pliku.']);
            exit;
        }

        try {
            $ocrData = $this->ocrService->processReceipt($file['tmp_name'], $userId);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'text' => $ocrData['raw_text'],
                'extracted' => [
                    'amount' => $ocrData['amount'],
                    'date' => $ocrData['date'],
                    'categoryId' => $ocrData['category_id']
                ]
            ]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}
