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
            'cssLink2' => 'main.css',
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
            $email = trim($_POST['email']);

            $this->userService->isEmailTaken($email);
            $this->userService->updateEmail($email, $userId);

            $_SESSION['success'] = 'Your email has been updated successfully!';
            unset($_SESSION['activeForm']);
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectTo);
        }

        redirectTo($redirectTo);
    }

    public function updateUsername()
    {
        $redirectTo = $_POST['redirect_to'] ?? '/settings';
        $userId = $_SESSION['user'] ?? null;

        $_SESSION['activeForm'] = $_POST['form_type'] ?? 'updateUsername';

        try {
            $this->validatorService->validateUsername($_POST);
            $newUsername = trim($_POST['username']);

            $this->userService->updateUsername($userId, $newUsername);

            $_SESSION['success'] = 'Your Username has been updated successfully!';
            unset($_SESSION['activeForm']);
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectTo);
        }

        redirectTo($redirectTo);
    }

    public function updatePassword()
    {
        $redirectTo = $_POST['redirect_to'] ?? '/settings';
        $userId = $_SESSION['user'] ?? null;

        $_SESSION['activeForm'] = $_POST['form_type'] ?? 'updatePassword';

        try {
            $this->validatorService->validateNewPassword($_POST);
            $newPassword = $_POST['password'];

            $this->userService->updatePassword($userId, $newPassword);

            $_SESSION['success'] = 'Your password has been updated successfully!';
            unset($_SESSION['activeForm']);
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectTo);
        }

        redirectTo($redirectTo);
    }

    public function deleteAccount()
    {

        $userId = $_SESSION['user'] ?? null;

        $this->userService->deleteById($userId);

        session_destroy();

        session_start();
        $_SESSION['success'] = 'Your account has been permanently deleted.';

        redirectTo('/');
    }
}
