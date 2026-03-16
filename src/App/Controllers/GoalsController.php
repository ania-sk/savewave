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

        $contributions = $this->goalService->getUserContributions($userId);

        $balanceData = $this->transactionService->getBalance($userId);
        $balance = $balanceData['balance'];

        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        echo $this->view->render("/goals.php", [
            'title' => 'Goals',
            'cssLink' => 'mainPage.css',
            'cssLink2' => 'goals.css',
            'jsLink' => 'goals.js',
            'goals' => $goals,
            'goalToEdit' => $goalToEdit,
            'contributions' => $contributions,
            'balance' => $balance,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
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
        $errors = $this->validatorService->validateContribution($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectPath);
        }

        $this->goalService->store($formData);

        unset($_SESSION['errors']);
        unset($_SESSION['oldFormData']);

        redirectTo($redirectPath);
    }

    public function updateContribution()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
        $formData = $_POST;
        $errors = $this->validatorService->validateContribution($formData);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectPath);
        }

        $this->goalService->updateContribution($formData);

        unset($_SESSION['errors']);
        unset($_SESSION['oldFormData']);

        redirectTo($redirectPath);
    }

    public function deleteContribution($params)
    {
        $this->goalService->deleteContribution((int)$params['contribution']);

        redirectTo('/goals');
    }
}
