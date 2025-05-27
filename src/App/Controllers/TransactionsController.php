<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValidatorService;
use Framework\Exceptions\ValidationException;

class TransactionsController
{
    public function __construct(
        private ValidatorService $validatorService
    ) {}

    public function addIncome()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateIncome($_POST);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
                header("Location: /mainPage");
                exit();
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = $_POST['form_type'] ?? 'income';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;

                header("Location: /mainPage");
                exit();
            }
        }
    }

    public function addExpense()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Próba walidacji danych dla formularza income
                $this->validatorService->validateExpense($_POST);

                // Jeśli walidacja się powiodła – wykonaj dalsze operacje np. zapis do bazy
                // ...
                header("Location: /mainPage");
                exit();
            } catch (ValidationException $ex) {
                $_SESSION['activeForm'] = $_POST['form_type'] ?? 'expense';
                $_SESSION['errors'] = $ex->errors;
                $_SESSION['oldFormData'] = $_POST;

                header("Location: /mainPage");
                exit();
            }
        }
    }
}
