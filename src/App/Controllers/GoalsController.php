<?php

declare(strict_types=1);

namespace App\Controllers;


use Framework\TemplateEngine;
use App\Services\{GoalService, ValidatorService};

class GoalsController
{
    public function __construct(
        private TemplateEngine $view,
        private GoalService $goalService,
        private ValidatorService $validatorService
    ) {}

    public function addGoal()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';

        $this->validatorService->validateNewGoal($_POST);

        $newGoalId = $this->goalService->createNewGoal($_POST);

        header("Location: " . $redirectPath);
        exit();
    }

    public function goals()
    {
        $userId = (int)$_SESSION['user'];
        $goals = $this->goalService->getUserGoals($userId);

        echo $this->view->render("/goals.php", [
            'title' => 'Goals',
            'cssLink' => 'mainPage.css',
            'cssLink2' => 'goals.css',
            'goals' => $goals


        ]);
    }
}
