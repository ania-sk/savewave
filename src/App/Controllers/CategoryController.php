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

            $redirectPath = $_POST['redirect_to'] ?? '/homePage';
            $formData = $_POST;

            try {
                $this->validatorService->validateNewIncomeCategory($formData);
                $this->categoryService->createUserIncomeCategory($formData);

                $_SESSION['success'] = 'Category has been added successfully!';

                redirectTo($redirectPath);
            } catch (ValidationException $ex) {

                $_SESSION['activeForm'] = 'addIncomeCategory';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $formData;
                $_SESSION['newCategoryName'] = $formData['newCategoryName'];

                redirectTo($redirectPath);
            }
        }
    }

    public function addNewIncomeCategoryAjax()
    {
        $newCategoryName = $_POST['newCategoryName'];
        $formData = $_POST;

        try {
            $this->validatorService->validateNewIncomeCategory($formData);
            $newCategoryId = $this->categoryService->createUserIncomeCategory($formData);
            header('Content-Type: application/json');
            echo json_encode([
                'id' => $newCategoryId,
                'name' => $newCategoryName
            ]);
            exit;
        } catch (ValidationException $ex) {

            header('Content-Type: application/json');
            http_response_code(422);
            echo json_encode([
                'errors' => $ex->errors
            ]);
            exit;
        }
    }

    public function addNewExpenseCategoryAjax()
    {
        $newCategoryName = $_POST['newCategoryName'];
        $formData = $_POST;

        try {
            $this->validatorService->validateNewExpenseCategory($formData);
            $newCategoryId = $this->categoryService->createUserExpenseCategory($formData);

            header('Content-Type: application/json');
            echo json_encode([
                'id' => $newCategoryId,
                'name' => $newCategoryName
            ]);
            exit;
        } catch (ValidationException $ex) {

            header('Content-Type: application/json');
            http_response_code(422);
            echo json_encode([
                'errors' => $ex->errors
            ]);
            exit;
        }
    }


    public function addNewExpenseCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $redirectPath = $_POST['redirect_to'] ?? '/homePage';

            try {
                $this->validatorService->validateNewExpenseCategory($_POST);

                $this->categoryService->createUserExpenseCategory($_POST);

                $_SESSION['success'] = 'Category has been added successfully!';

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
                $_POST['newCategoryName'] = trim($_POST['newCategoryName'] ?? '');
                $this->validatorService->validateNewIncomeCategory($_POST);
            } elseif ($categoryType === 'expense') {
                $_POST['newCategoryName'] = trim($_POST['newCategoryName'] ?? '');
                $this->validatorService->validateNewExpenseCategory($_POST);
            } else {
                redirectTo($redirectTo);
            }
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $_POST;
            $_SESSION['categoryToEdit'] = $categoryToEdit;
            redirectTo($redirectTo);
        }

        $newName = trim($_POST['newCategoryName']);

        if ($categoryType === 'income') {
            $this->categoryService->updateUserIncomeCategory($categoryId, $newName);
        } else {
            $this->categoryService->updateUserExpenseCategory($categoryId, $newName);
        }

        $_SESSION['success'] = 'Category has been updated successfully!';

        unset($_SESSION['activeForm'], $_SESSION['categoryToEdit']);
        redirectTo($redirectTo);
    }

    public function addCategoryLimit(array $params): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = (int) $params['category'];
            $categoryType = $_POST['categoryType'] ?? null;
            $redirectTo = $_POST['redirect_to'] ?? '/settings';
            $limitRaw = $_POST['monthly_limit'] ?? null;

            $_SESSION['activeForm'] = 'limitModal';
            $_SESSION['categoryToEdit'] = $this->categoryService->getUserCategoryById($categoryId);

            if (!is_numeric($limitRaw) || $limitRaw < 0) {
                $_SESSION['errors']['monthly_limit'][] = 'Limit must be a non-negative number.';
                $_SESSION['oldFormData'] = $_POST;
                redirectTo($redirectTo);
            }

            $limit = (float) $limitRaw;

            try {
                $this->categoryService->updateCategoryLimit($categoryId, $categoryType, $limit);
                $_SESSION['success'] = 'Monthly limit has been updated successfully!';
                unset($_SESSION['activeForm'], $_SESSION['categoryToEdit'], $_SESSION['oldFormData']);
            } catch (\Throwable $e) {
                $_SESSION['errors']['monthly_limit'][] = 'Failed to update limit.';
            }

            redirectTo($redirectTo);
        }
    }

    public function checkCategoryLimit()
    {
        header('Content-Type: application/json');

        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
        $amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
        $expenseId = isset($_GET['expense_id']) ? (int)$_GET['expense_id'] : null;
        $userId = $_SESSION['user'] ?? 0;

        if (!$categoryId || !$userId) {
            echo json_encode(['error' => 'Invalid parameters']);
            return;
        }

        $categoryLimit = $this->categoryService->getCategoryLimit($categoryId, $userId);

        // If the category has no limit, return OK
        if ($categoryLimit === null || $categoryLimit === 0.00) {
            echo json_encode([
                'hasLimit' => false,
                'status' => 'ok'
            ]);
            return;
        }

        // Calculate the total expenses in the category (without the edited expense)
        $currentTotalExpense = $this->categoryService->getMonthlyCategoryTotalExpense($userId, $categoryId, $expenseId);

        $newTotalExpense = $currentTotalExpense + $amount;

        $percentage = $categoryLimit > 0 ? ($newTotalExpense / $categoryLimit) * 100 : 0;

        $status = 'ok';
        $level = 'success';

        if ($percentage >= 100) {
            $status = 'exceeded';
            $level = 'danger';
        } elseif ($percentage >= 80) {
            $status = 'warning';
            $level = 'warning';
        }

        echo json_encode([
            'hasLimit' => true,
            'limit' => $categoryLimit,
            'currentTotalExpense' => $currentTotalExpense,
            'newTotalExpense' => $newTotalExpense,
            'percentage' => $percentage,
            'status' => $status,
            'level' => $level,
            'message' => $this->getLimitMessage($status, $newTotalExpense, $categoryLimit, $percentage)
        ]);
    }

    private function getLimitMessage(string $status, float $newTotalExpense, float $categoryLimit, float $percentage): string
    {
        if ($status === 'exceeded') {
            $over = $newTotalExpense - $categoryLimit;
            return "⚠️ Warning! This expense will exceed your category limit by " . number_format($over, 2) . " PLN (" . round($percentage, 1) . "% of limit).";
        } elseif ($status === 'warning') {
            return "⚠️ Caution! You're approaching your category limit (" . round($percentage, 1) . "% used).";
        }
        return "✓ Within budget (" . round($percentage, 1) . "% of limit used).";
    }

    public function deleteCategory(array $params)
    {
        $id   = (int) $params['category'];
        $type = $_POST['categoryType'] ?? null;
        $redirectTo = $_POST['redirect_to'] ?? '/settings';
        $this->categoryService->deactivateCategory($id, $type);

        $_SESSION['success'] = 'Category has been deleted successfully!';

        redirectTo($redirectTo);
    }
}
