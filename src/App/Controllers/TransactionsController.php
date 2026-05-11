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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $redirectPath = $_POST['redirect_to'] ?? '/homePage';
            $formData = $_POST;
            $userId = $_SESSION['user'];

            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateIncome($formData);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
                $this->transactionService->createIncome($formData, $userId);
                redirectTo($redirectPath);
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = $_POST['form_type'] ?? 'income';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;

                redirectTo($redirectPath);
            }
        }
    }

    public function addExpense()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $redirectPath = $_POST['redirect_to'] ?? '/homePage';
            $formData = $_POST;
            $userId = $_SESSION['user'];

            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateExpense($formData);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
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
}
