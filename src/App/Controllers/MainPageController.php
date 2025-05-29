<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\CategoryService;
use App\Config\Paths;

class MainPageController
{
    private CategoryService $categoryService;
    public function __construct(private TemplateEngine $view, CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function mainPage()
    {
        $userId = (int)$_SESSION['user'];
        $incomeCategories = $this->categoryService->getUserIncomeCategories($userId);
        $expenseCategories = $this->categoryService->getUserExpenseCategories($userId);

        echo $this->view->render("/mainPage.php", [
            'title' => 'Budget Application',
            'cssLink' => 'mainPage.css',
            'cssLink2' => '',
            'jsLink' => '',
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories
        ]);
    }
}
