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
    DateFormatRule,
    UniqueCategoryRule
};
use App\Services\CategoryService;

class ValidatorService
{
    private Validator $validator;
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
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

    public function validateNewIncomeCategory(array $formData)
    {
        $existingCategories = $this->categoryService->getUserActiveIncomeCategories($_SESSION['user']);
        $uniqueRule = new UniqueCategoryRule($existingCategories);
        $this->validator->add('uniqueName', $uniqueRule);
        $this->validator->validate($formData, [
            'newCategoryName' => ['required', 'uniqueName']
        ]);
    }

    public function validateNewExpenseCategory(array $formData)
    {
        $existingCategories = $this->categoryService->getUserActiveExpenseCategories($_SESSION['user']);
        $uniqueRule = new UniqueCategoryRule($existingCategories);
        $this->validator->add('uniqueName', $uniqueRule);
        $this->validator->validate($formData, [
            'newCategoryName' => ['required', 'uniqueName']
        ]);
    }

    public function validateUpdateEmail(array $formData): void
    {
        $this->validator->validate($formData, [
            'email' => ['required', 'email']
        ]);
    }

    public function validateUsername(array $formData)
    {
        $this->validator->validate($formData, [
            'username' => ['required']
        ]);
    }

    public function validateNewPassword(array $formData)
    {
        $this->validator->validate($formData, [
            'password' => ['required'],
            'confirm-password' => ['required', 'match:password']
        ]);
    }
}
