<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, UserService, GoalService};
use App\Config\Paths;

class HomePageController
{

    public function __construct(private TemplateEngine $view, private CategoryService $categoryService, private UserService $userService, private GoalService $goalService) {}

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

        echo $this->view->render("/homePage.php", [
            'title' => 'Budget Application',
            'cssLink' => 'homePage.css',
            'cssLink2' => '',
            'jsLink' => '',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'username' => $username,
            'goals' => $goalsToDisplay,
        ]);
    }
}
