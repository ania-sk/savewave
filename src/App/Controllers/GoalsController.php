<?php

declare(strict_types=1);

namespace App\Controllers;


use Framework\TemplateEngine;
use App\Services\{GoalService, ValidatorService, TransactionService, CategoryService};
use Framework\Exceptions\ValidationException;

class GoalsController
{
    public function __construct(
        private TemplateEngine $view,
        private GoalService $goalService,
        private ValidatorService $validatorService,
        private TransactionService $transactionService,
        private CategoryService $categoryService
    ) {}

    public function addGoal()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';

        try {
            $this->validatorService->validateNewGoal($_POST);
            $this->goalService->createNewGoal($_POST);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $_POST;
            $_SESSION['activeForm'] = $_POST['form_type'];
            redirectTo($redirectPath);
        }
    }

    public function goals()
    {
        $userId = (int)$_SESSION['user'];
        $goals = $this->goalService->getUserGoals($userId);
        $goalToEdit = $_SESSION['goalToEdit'] ?? null;

        $allContributions = $this->goalService->getUserContributions($userId);

        $balanceData = $this->transactionService->getBalance($userId);
        $balance = $balanceData['balance'];

        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        //pagination for the contributions table
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $totalRecords = count($allContributions);
        $totalPages = (int)ceil($totalRecords / $limit);

        $contributions = array_slice($allContributions, $offset, $limit);

        echo $this->view->render("/goals.php", [
            'title' => 'Goals',
            'cssLink' => 'goals.css',
            'cssLink2' => 'main.css',
            'jsLink' => 'goals.js',
            'goals' => $goals,
            'goalToEdit' => $goalToEdit,
            'contributions' => $contributions,
            'balance' => $balance,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'offset' => $offset
        ]);
    }

    public function getGoalContributions($params)
    {
        $goalId = (int)$params['goal'];
        $contributions = $this->goalService->getGoalContributions($goalId);

        header('Content-Type: application/json');
        echo json_encode($contributions);
    }

    public function updateGoal()
    {
        $formData = $_POST;
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';

        try {
            $this->validatorService->validateNewGoal($formData);
            $this->goalService->updateGoal($formData);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $formData;
            $_SESSION['activeForm'] = $formData['form_type'];
            redirectTo($redirectPath);
        }
    }

    public function deleteGoal($params)
    {
        $this->goalService->deleteGoal($params['goal']);

        redirectTo('/goals');
    }

    public function store()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
        $formData = $_POST;
        $userId = $_SESSION['user'];
        $balance = $this->transactionService->getBalance($userId)['balance'];

        try {

            $this->validatorService->validateContribution($formData, $balance);
            $this->goalService->store($formData);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {

            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $formData;
            $_SESSION['activeForm'] = $formData['form_type'];
            redirectTo($redirectPath);
        }
    }

    public function updateContribution()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
        $formData = $_POST;
        $userId = $_SESSION['user'];
        $balance = $this->transactionService->getBalance($userId)['balance'];

        try {
            $this->validatorService->validateChangeContribution($formData, $balance);
            $this->goalService->updateContribution($formData);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {

            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $formData;
            $_SESSION['activeForm'] = $formData['form_type'];
            redirectTo($redirectPath);
        }
    }

    public function deleteContribution($params)
    {
        $this->goalService->deleteContribution((int)$params['contribution']);

        redirectTo('/goals');
    }
}
