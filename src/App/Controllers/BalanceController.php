<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{CategoryService, TransactionService};
use Framework\TemplateEngine;

class   BalanceController
{
    public function __construct(
        private TemplateEngine $view,
        private CategoryService $categoryService,
        private TransactionService $transactionService
    ) {}

    public function balance()
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
            $expensesSumsByCat = $this->transactionService->getExpenseSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);
            $totalExpense = array_sum(array_column($expenses, 'amount'));

            $incomes = $this->transactionService->getUserIncomesByDateRange($userId, $startDate, $endDate);
            $incomesSumsByCat = $this->transactionService->getIncomeSumsByCategoryAndDateRange($userId, $startDate, $endDate);
            $totalIncome = array_sum(array_column($incomes, 'amount'));

            $balance = $totalIncome - $totalExpense;
        } else {
            $expenses = $this->transactionService->getUserExpenses($userId);
            $expensesSumsByCat = $this->transactionService->getExpenseSumsByCategory($userId);
            $totalExpense = array_sum(array_column($expenses, 'amount'));

            $incomes = $this->transactionService->getUserIncomes($userId);
            $incomesSumsByCat = $this->transactionService->getIncomeSumsByCategory($userId);
            $totalIncome = array_sum(array_column($incomes, 'amount'));
            $balance = $totalIncome - $totalExpense;
        }

        $transactions = [];


        foreach ($incomes as $income) {
            $transactions[] = [
                'type' => 'Income',
                'category' => $income['name'],
                'date' => $income['formatted_date'],
                'amount' => $income['amount']
            ];
        }

        foreach ($expenses as $expense) {
            $transactions[] = [
                'type' => 'Expense',
                'category' => $expense['name'],
                'date' => $expense['formatted_date'],
                'amount' => $expense['amount']
            ];
        }

        usort($transactions, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

        echo $this->view->render("/balance.php", [
            'title' => 'Balance',
            'cssLink' => 'mainPage.css',
            'cssLink2' => 'balance.css',
            'jsLink' => 'balance.js',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'expenseChartLabels' => array_column($expensesSumsByCat, 'category'),
            'expenseChartData' => array_map(fn($r) => (float)$r['total'], $expensesSumsByCat),
            'incomeChartLabels' => array_column($incomesSumsByCat, 'category'),
            'incomeChartData' => array_map(fn($r) => (float)$r['total'], $incomesSumsByCat),
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'transactions' => $transactions

        ]);
    }
}
