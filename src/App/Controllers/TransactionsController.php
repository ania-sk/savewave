<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionService};
use Framework\Exceptions\ValidationException;

class TransactionsController
{
    public function __construct(
        private ValidatorService $validatorService,
        private TransactionService $transactionService,
        private TemplateEngine $view
    ) {}

    public function addIncome()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
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

                header("Location: " . $redirectPath);
                exit();
            }
        }
    }

    public function addExpense()
    {
        $redirectPath = $_POST['redirect_to'] ?? '/mainPage';
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

    public function editIncome(array $params)
    {
        $income = $this->transactionService->getUserIncome($params['income']);

        if (!$income) {
            redirectTo('/incomes');
        }

        echo $this->view->render('incomes.php', [
            'income' => $income
        ]);
    }

    // public function deleteIncome(array $params)
    // {
    //     $this->transactionService->deleteIncome((int) $params['income']);
    // }
}
