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
        // FIXME - AI CR - [W11 WARNING][Jakość kodu] Zbędne sprawdzanie REQUEST_METHOD — router już gwarantuje POST. Dotyczy też addExpense().
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $redirectPath = $_POST['redirect_to'] ?? '/homePage';
            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateIncome($_POST);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
                $this->transactionService->createIncome($_POST);
                redirectTo($redirectPath);
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = $_POST['form_type'] ?? 'income';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;

                // FIXME - AI CR - [W12 WARNING][Jakość kodu] Użyj redirectTo($redirectPath) zamiast ręcznego header()+exit(). Niespójne z resztą kodu. Dotyczy też addExpense() i addNewExpenseCategory().
                header("Location: " . $redirectPath);
                exit();
            }
        }
    }

    public function addExpense()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/homePage';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateExpense($_POST);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
                $this->transactionService->createExpense($_POST);
                redirectTo($redirectPath);
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = $_POST['form_type'] ?? 'expense';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;

                header("Location: " . $redirectPath);
                exit();
            }
        }
    }
}
