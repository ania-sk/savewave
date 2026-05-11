<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, UserService, GoalService, TransactionService};
use App\Config\Paths;

class HomePageController
{

    public function __construct(private TemplateEngine $view, private CategoryService $categoryService, private UserService $userService, private GoalService $goalService, private TransactionService $transactionService) {}

    public function homePage()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $username =  $this->userService->getUsername($userId);

        $goals = $this->goalService->getUserGoals($userId);
        $activeGoals = [];
        $achievedGoals = [];

        foreach ($goals as $goal) {
            if ($goal['amount_saved'] < $goal['amount_needed']) {
                $activeGoals[] = $goal;
            } else {
                $achievedGoals[] = $goal;
            }
        }


        if (!empty($activeGoals)) {
            $goalsToDisplay =   array_slice($activeGoals, 0, 4);
            if (count($goalsToDisplay) < 4) {
                $remaining = 4 - count($goalsToDisplay);
                $goalsToDisplay = array_merge(
                    $goalsToDisplay,
                    array_slice($achievedGoals, 0, $remaining)
                );
            }
        } else {
            $goalsToDisplay = array_slice($achievedGoals, 0, 4);
        }

        $latestIncomes = array_slice($this->transactionService->getUserIncomes($userId), 0, 5);
        $latestExpenses = array_slice($this->transactionService->getUserExpenses($userId), 0, 5);
        $latestCotributions = array_slice($this->goalService->getUserContributions($userId), 0, 5);
        $allBalance = $this->transactionService->getBalance($userId);
        $balance = $allBalance['balance'];
        $totalIncomes = $allBalance['totalIncome'];
        $totalExpenses = $allBalance['totalExpense'];

        echo $this->view->render("/homePage.php", [
            'title' => 'Budget Application',
            'cssLink' => 'homePage.css',
            'cssLink2' => 'main.css',
            'jsLink' => 'balance.js',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'username' => $username,
            'goals' => $goalsToDisplay,
            'incomes' => $latestIncomes,
            'expenses' => $latestExpenses,
            'contributions' => $latestCotributions,
            'balance' => $balance,
            'incomeSum' => $totalIncomes,
            'expenseSum' => $totalExpenses
        ]);
    }
}
