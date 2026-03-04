<?php

declare(strict_types=1);

namespace App\Controllers;


use Framework\TemplateEngine;
use App\Services\GoalService;

class GoalsController
{
    public function __construct(
        private TemplateEngine $view,
        private GoalService $goalService
    ) {}

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
