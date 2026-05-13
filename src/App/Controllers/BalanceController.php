<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{CategoryService, GoalService, TransactionService};
use Framework\TemplateEngine;

class BalanceController
{
    public function __construct(
        private TemplateEngine $view,
        private CategoryService $categoryService,
        private TransactionService $transactionService,
        private GoalService $goalService
    ) {}

    public function balance()
    {
        $userId = (int)$_SESSION['user'];

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate  = trim($_GET['end_date']   ?? '');

        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $balanceData = $this->transactionService->getBalance($userId, $startDate, $endDate);

        //pagination for the transaction table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        // For balance view, we still need to build full history for proper aggregation across types
        // but we'll paginate the final result carefully
        $allTransactions = $this->transactionService->buildTransactionHistory(
            $balanceData['incomes'],
            $balanceData['expenses'],
            $balanceData['contributions']
        );

        $totalRecords = count($allTransactions);
        $totalPages = (int)ceil($totalRecords / $limit);

        $transactions = array_slice($allTransactions, $offset, $limit);

        // data for charts
        $expenseChartLabels = array_column($balanceData['expensesSumsByCat'], 'category');
        $expenseChartData = array_map(fn($r) => (float)$r['total'], $balanceData['expensesSumsByCat']);

        $incomeChartLabels = array_column($balanceData['incomesSumsByCat'], 'category');
        $incomeChartData = array_map(fn($r) => (float)$r['total'], $balanceData['incomesSumsByCat']);

        $goalChartLabels = array_column($balanceData['contributionsSumsByGoal'], 'goal');
        $goalChartData = array_map(fn($r) => (float)$r['total'], $balanceData['contributionsSumsByGoal']);

        echo $this->view->render("/balance.php", [
            'title' => 'Balance',
            'cssLink' => 'balance.css',
            'cssLink2' => 'main.css',
            'jsLink' => 'balance.js',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'expenseChartLabels' => $expenseChartLabels,
            'expenseChartData' => $expenseChartData,
            'incomeChartLabels' => $incomeChartLabels,
            'incomeChartData' => $incomeChartData,
            'goalChartLabels' => $goalChartLabels,
            'goalChartData' => $goalChartData,
            'totalIncome' => $balanceData['totalIncome'],
            'totalExpense' => $balanceData['totalExpense'],
            'totalContributions' => $balanceData['totalContributions'],
            'balance' => $balanceData['balance'],
            'transactions' => $transactions,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'offset' => $offset

        ]);
    }
}
