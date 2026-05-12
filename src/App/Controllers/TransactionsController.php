<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{ValidatorService, TransactionService};
use Framework\Exceptions\ValidationException;

class TransactionsController
{
    public function __construct(
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {}

    public function addIncome()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/homePage';
        $formData = $_POST;
        $userId = $_SESSION['user'];

        try {
            $this->validatorService->validateIncome($formData);
            $this->transactionService->createIncome($formData, $userId);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['activeForm'] = $_POST['form_type'] ?? 'income';
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $_POST;

            redirectTo($redirectPath);
        }
    }

    public function addExpense()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/homePage';
        $formData = $_POST;
        $userId = $_SESSION['user'];

        try {
            $this->validatorService->validateExpense($formData);
            $this->transactionService->createExpense($formData, $userId);
            redirectTo($redirectPath);
        } catch (ValidationException $ex) {
            $_SESSION['activeForm'] = $_POST['form_type'] ?? 'expense';
            $_SESSION['errors'] = $ex->errors;
            $_SESSION['oldFormData'] = $_POST;

            redirectTo($redirectPath);
        }
    }
}
