<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ValidatorService;

class TransactionsController
{
    public function __construct(
        private ValidatorService $validatorService
    ) {}

    public function addIncome()
    {
        $this->validatorService->validateIncome($_POST);
    }

    public function addExpense()
    {
        $this->validatorService->validateExpense($_POST);
    }
}
