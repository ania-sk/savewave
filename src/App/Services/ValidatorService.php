<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{RequiredRule, EmailRule, MatchRule, LengthMaxRule, NumericRule};

class ValidatorService
{
    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        $this->validator->add('required', new RequiredRule());
        $this->validator->add('email', new EmailRule());
        $this->validator->add('match', new MatchRule());
        $this->validator->add('lengthMax', new LengthMaxRule());
        $this->validator->add('numeric', new NumericRule());
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'username' => ['required'],
            'password' => ['required'],
            'confirm-password' => ['required', 'match:password']

        ]);
    }

    public function validateLogin(array $formData)
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    }

    public function validateIncome(array $formData)
    {
        $this->validator->validate($formData, [
            'incomeAmount' => ['required', 'numeric'],
            'incomeDate' => ['required'],
            'incomeComment' => ['lengthMax:255'],
            'incomeCategory' => ['required']
        ]);
    }

    public function validateExpense(array $formData)
    {
        $this->validator->validate($formData, [
            'expenseAmount' => ['required', 'numeric'],
            'expenseDate' => ['required'],
            'expenseComment' => ['lengthMax:255'],
            'expenseCategory' => ['required']
        ]);
    }
}
