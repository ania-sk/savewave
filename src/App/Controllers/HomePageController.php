<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{CategoryService, UserService};
use App\Config\Paths;

class HomePageController
{

    public function __construct(private TemplateEngine $view, private CategoryService $categoryService, private UserService $userService) {}

    public function homePage()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserActiveIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserActiveExpenseCategories($userId);

        $username =  $this->userService->getUsername($userId);

        echo $this->view->render("/homePage.php", [
            'title' => 'Budget Application',
            'cssLink' => 'homePage.css',
            'cssLink2' => '',
            'jsLink' => '',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'username' => $username
        ]);
    }
}
