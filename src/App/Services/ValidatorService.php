<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;
use Framework\Validator;
use Framework\Rules\{
    RequiredRule,
    EmailRule,
    MatchRule,
    LengthMaxRule,
    NumericRule,
    DateFormatRule
};

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
        $this->validator->add('dateFormat', new DateFormatRule());
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
            'incomeDate' => ['required', 'dateFormat:Y-m-d'],
            'incomeComment' => ['lengthMax:255'],
            'incomeCategory' => ['required']
        ]);
    }

    public function validateExpense(array $formData)
    {
        $this->validator->validate($formData, [
            'expenseAmount' => ['required', 'numeric'],
            'expenseDate' => ['required', 'dateFormat:Y-m-d'],
            'expenseComment' => ['lengthMax:255'],
            'expenseCategory' => ['required']
        ]);
    }

    public function validateNewCategory(array $formData)
    {
        $this->validator->validate($formData, [
            'newCategoryName' => ['required']
        ]);
    }
}
