<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{ValidatorService, CategoryService};
use Framework\Exceptions\ValidationException;

class CategoryController
{
    public function __construct(
        private ValidatorService $validatorService,
        private CategoryService $categoryService
    ) {}

    public function addNewIncomeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $redirectPath = $_POST['redirect_to'] ?? '/mainPage';

            try {
                $this->validatorService->validateNewIncomeCategory($_POST);

                $this->categoryService->createUserIncomeCategory($_POST);

                redirectTo($redirectPath);
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = 'addIncomeCategory';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;
                $_SESSION['newCategoryName'] = $_POST;

                header("Location: " . $redirectPath);
                exit();
            }
        }
    }

    public function addNewExpenseCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $redirectPath = $_POST['redirect_to'] ?? '/mainPage';

            try {
                $this->validatorService->validateNewExpenseCategory($_POST);

                $this->categoryService->createUserExpenseCategory($_POST);

                redirectTo($redirectPath);
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = 'addExpenseCategory';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;
                $_SESSION['newCategoryName'] = $_POST;

                header("Location: " . $redirectPath);
                exit();
            }
        }
    }

    public function editCategory(array $params)
    {
        $categoryId = (int)$params['category'];
        $categoryToEdit = $this->categoryService->getUserCategoryById($categoryId);

        if (!$categoryToEdit) {
            redirectTo('/settings');
        }

        $_SESSION['activeForm'] = 'editCategory';
        $_SESSION['categoryToEdit'] = $categoryToEdit;

        redirectTo('/settings');
    }

    public function updateCategory(array $params)
    {
        $categoryId = (int) $params['category'];
        $categoryType = $_POST['categoryType'] ?? null;
        $redirectTo = $_POST['redirect_to'] ?? '/settings';

        $categoryToEdit = $this->categoryService->getUserCategoryById($categoryId);
        $_SESSION['activeForm'] = 'editCategory';
        $_SESSION['categoryToEdit'] = $categoryToEdit;


        try {
            if ($categoryType === 'income') {
                $_POST['newCategoryName'] = trim($_POST['categoryName'] ?? '');
                $this->validatorService->validateNewIncomeCategory($_POST);
            } elseif ($categoryType === 'expense') {
                $_POST['newCategoryName'] = trim($_POST['categoryName'] ?? '');
                $this->validatorService->validateNewExpenseCategory($_POST);
            } else {
                redirectTo($redirectTo);
            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            redirectTo($redirectTo);
        }

        $newName = trim($_POST['categoryName']);

        if ($categoryType === 'income') {
            $this->categoryService->updateUserIncomeCategory($categoryId, $newName);
        } else {
            $this->categoryService->updateUserExpenseCategory($categoryId, $newName);
        }

        unset($_SESSION['activeForm'], $_SESSION['categoryToEdit']);
        redirectTo($redirectTo);
    }
}
