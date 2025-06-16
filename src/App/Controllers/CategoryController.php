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

                $this->categoryService->createUserCategory($_POST);

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
}
