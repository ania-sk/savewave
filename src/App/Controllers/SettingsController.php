<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, ValidatorService, UserService};

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
        $incomeCategories = $this->categoryService->getUserIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserExpenseCategories($userId);
        $email = $this->userService->getUserEmail($userId);
        $username = $this->userService->getUsername($userId);

        echo $this->view->render("/settings.php", [
            'title' => 'Settings',
            'cssLink' => 'settings.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => '',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'email' => $email,
            'username' => $username
        ]);
    }
}
