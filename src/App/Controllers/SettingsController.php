<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, ValidatorService, UserService};
use Framework\Exceptions\ValidationException;

class SettingsController
{
    public function __construct(
        private TemplateEngine $view,
        private UserService $userService,
        private CategoryService $categoryService,
        private ValidatorService $validatorService
    ) {}

    public function settings()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);
        $email = $this->userService->getUserEmail($userId);
        $username = $this->userService->getUsername($userId);

        echo $this->view->render("/settings.php", [
            'title' => 'Settings',
            'cssLink' => 'settings.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'settings.js',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'email' => $email,
            'username' => $username
        ]);
    }

    public function updateEmail()
    {
        $redirectTo = $_POST['redirect_to'] ?? '/settings';
        $userId = $_SESSION['user'] ?? null;

        $_SESSION['activeForm'] = 'updateEmail';

        try {
            $this->validatorService->validateUpdateEmail($_POST);
        } catch (ValidationException $e) {

            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectTo);
        }

        $email = trim($_POST['email']);

        $this->userService->isEmailTaken($email);
        $this->userService->updateEmail($email, $userId);

        $_SESSION['success'] = 'Your email has been updated successfully!';

        unset($_SESSION['activeForm']);

        redirectTo($redirectTo);
    }

    public function updateUsername()
    {
        $redirectTo = '/settings';

        $userId = $_SESSION['user'] ?? null;
        $_SESSION['activeForm'] = $_POST['form_type'] ?? null;

        $newUsername = trim($_POST['username']);

        $this->validatorService->validateUsername($_POST);

        $this->userService->updateUsername($userId, $newUsername);

        $_SESSION['success'] = 'Your Username has been updated successfully!';

        unset($_SESSION['activeForm']);

        redirectTo($redirectTo);
    }
}
