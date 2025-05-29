<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db) {}

    public function createIncome(array $formData)
    {
        $formattedDate = "{$formData['incomeDate']} 00:00:00";
        $this->db->query(
            "INSERT INTO incomes(
                user_id, 
                income_category_assigned_to_user_id, 
                amount, 
                date_of_income, 
                income_comment
            ) VALUES(
                :user_id, 
                :income_category_assigned_to_user_id, 
                :amount, 
                :date_of_income, 
                :income_comment
            )",
            [
                'user_id' => $_SESSION['user'],
                'income_category_assigned_to_user_id' => $formData['incomeCategory'],
                'amount' => $formData['incomeAmount'],
                'date_of_income' => $formattedDate,
                'income_comment' => $formData['incomeComment']
            ]
        );
    }

    public function createExpense(array $formData)
    {
        $formattedDate = "{$formData['expenseDate']} 00:00:00";
        $this->db->query(
            "INSERT INTO expenses(
                user_id, 
                expense_category_assigned_to_user_id, 
                amount, 
                date_of_expense, 
                expense_comment
            ) VALUES(
                :user_id, 
                :expense_category_assigned_to_user_id, 
                :amount, 
                :date_of_expense, 
                :expense_comment
            )",
            [
                'user_id' => $_SESSION['user'],
                'expense_category_assigned_to_user_id' => $formData['expenseCategory'],
                'amount' => $formData['expenseAmount'],
                'date_of_expense' => $formattedDate,
                'expense_comment' => $formData['expenseComment']
            ]
        );
    }

    public function getUserIncomes()
    {
        $incomes = $this->db->query(
            "SELECT 
                i.amount, 
                i.income_comment, 
                i.date_of_income, 
                c.name
            FROM incomes AS i
            JOIN incomes_category_assigned_to_users AS c 
            ON i.income_category_assigned_to_user_id = c.id
            WHERE i.user_id = :user_id",
            ['user_id' => $_SESSION['user']]
        )->fetchAll();

        return $incomes;
    }

    public function getUserExpenses() {}
}
