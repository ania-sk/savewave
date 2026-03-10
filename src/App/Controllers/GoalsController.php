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
        $goalToEdit = $_SESSION['goalToEdit'] ?? null;

        echo $this->view->render("/goals.php", [
            'title' => 'Goals',
            'cssLink' => 'mainPage.css',
            'cssLink2' => 'goals.css',
            'jsLink' => 'goals.js',
            'goals' => $goals,
            'goalToEdit' => $goalToEdit
        ]);
    }

    public function updateGoal($goalId)
    {

        $formData = $_POST;
        $this->goalService->updateGoal($formData);

        header("Location: /goals");
        exit();
    }

    // public function getGoal($id)
    // {
    //     $goal = $this->goalService->getGoalById($id);

    //     header('Content-Type: application/json');
    //     echo json_encode($goal);
    // }

    // public function editGoal(array $params): void
    // {
    //     $goalToEdit = $this->goalService->getUserGoal($params['goal']);

    //     if (!$goalToEdit) {
    //         redirectTo('/goals');
    //     }

    //     $_SESSION['goalToEdit'] = $goalToEdit;

    //     redirectTo('/goals');
    // }

    // public function updateGoal(array $params)
    // {
    //     $goalToEdit = $this->goalService->getUserGoal($params['goal']);

    //     if (!$goalToEdit) {
    //         redirectTo('/goals');
    //     }

    //     $errors = $this->validatorService->validateNewGoal($_POST);

    //     $this->goalService->updateGoal($_POST, $goalToEdit['id']);

    //     unset($_SESSION['goalToEdit']);

    //     redirectTo($_SERVER['HTTP_REFERER']);
    // }
}
