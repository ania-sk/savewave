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

    public function getUserIncomes($userId)
    {
        $incomes = $this->db->query(
            "SELECT 
                i.id,
                i.amount, 
                i.income_comment, 
                DATE_FORMAT(i.date_of_income, '%Y-%m-%d') as formatted_date,
                c.name
            FROM incomes AS i
            JOIN incomes_category_assigned_to_users AS c 
            ON i.income_category_assigned_to_user_id = c.id
            WHERE i.user_id = :user_id",
            ['user_id' => $userId]
        )->fetchAll();

        return $incomes;
    }

    public function getUserIncomesByDateRange(int $userId, string $startDate, string $endDate): array
    {
        $incomes =  $this->db->query(
            "SELECT
             i.id,
             i.amount, 
             i.income_comment, 
             DATE_FORMAT(i.date_of_income, '%Y-%m-%d') as formatted_date,
             c.name
           FROM incomes AS i
           JOIN incomes_category_assigned_to_users AS c
             ON i.income_category_assigned_to_user_id = c.id
          WHERE i.user_id = :uid
            AND i.date_of_income BETWEEN :start AND :end
          ORDER BY i.date_of_income DESC",
            [
                'uid'   => $userId,
                'start' => $startDate,
                'end'   => $endDate
            ]
        )->fetchAll();

        return $incomes;
    }

    public function getIncomeSumsByCategory(int $userId): array
    {
        $incomes = $this->db->query(
            "SELECT c.name AS category, 
            SUM(i.amount) AS total
           FROM incomes AS i
           JOIN incomes_category_assigned_to_users AS c
             ON i.income_category_assigned_to_user_id = c.id
          WHERE i.user_id = :uid
          GROUP BY i.income_category_assigned_to_user_id",
            ['uid' => $userId]
        )->fetchAll();

        return $incomes;
    }

    public function getIncomeSumsByCategoryAndDateRange(int $userId, string $startDate, string $endDate): array
    {
        $incomes = $this->db->query(
            "SELECT c.name AS category, 
            SUM(i.amount) AS total
           FROM incomes AS i
           JOIN incomes_category_assigned_to_users AS c
             ON i.income_category_assigned_to_user_id = c.id
          WHERE i.user_id = :uid
            AND i.date_of_income BETWEEN :start AND :end
          GROUP BY i.income_category_assigned_to_user_id",
            [
                'uid'   => $userId,
                'start' => $startDate,
                'end'   => $endDate,
            ]
        )->fetchAll();

        return $incomes;
    }

    public function getUserExpenses()
    {
        $expenses = $this->db->query(
            "SELECT 
                e.id,
                e.amount, 
                e.expense_comment, 
                DATE_FORMAT(e.date_of_expense, '%Y-%m-%d') as formatted_date,
                c.name
            FROM expenses AS e
            JOIN expenses_category_assigned_to_users AS c 
            ON e.expense_category_assigned_to_user_id = c.id
            WHERE e.user_id = :user_id",
            ['user_id' => $_SESSION['user']]
        )->fetchAll();

        return $expenses;
    }

    public function getUserExpensesByDateRange(int $userId, string $startDate, string $endDate): array
    {
        $expenses =  $this->db->query(
            "SELECT
             e.id,
             e.amount, 
             e.expense_comment, 
             DATE_FORMAT(e.date_of_expense, '%Y-%m-%d') as formatted_date,
             c.name
           FROM expenses AS e
           JOIN expenses_category_assigned_to_users AS c
             ON e.expense_category_assigned_to_user_id = c.id
          WHERE e.user_id = :uid
            AND e.date_of_expense BETWEEN :start AND :end
          ORDER BY e.date_of_expense DESC",
            [
                'uid'   => $userId,
                'start' => $startDate,
                'end'   => $endDate
            ]
        )->fetchAll();

        return $expenses;
    }

    public function getExpenseSumsByCategory(int $userId): array
    {
        $expenses = $this->db->query(
            "SELECT c.name AS category, 
            SUM(e.amount) AS total
           FROM expenses AS e
           JOIN expenses_category_assigned_to_users AS c
             ON e.expense_category_assigned_to_user_id = c.id
          WHERE e.user_id = :uid
          GROUP BY e.expense_category_assigned_to_user_id",
            ['uid' => $userId]
        )->fetchAll();

        return $expenses;
    }

    public function getExpenseSumsByCategoryAndDateRange(int $userId, string $startDate, string $endDate): array
    {
        $expenses = $this->db->query(
            "SELECT c.name AS category, 
            SUM(e.amount) AS total
           FROM expenses AS e
           JOIN expenses_category_assigned_to_users AS c
             ON e.expense_category_assigned_to_user_id = c.id
          WHERE e.user_id = :uid
            AND e.date_of_expense BETWEEN :start AND :end
          GROUP BY e.expense_category_assigned_to_user_id",
            [
                'uid'   => $userId,
                'start' => $startDate,
                'end'   => $endDate,
            ]
        )->fetchAll();

        return $expenses;
    }


    public function getUserIncome(string $id)
    {
        return $this->db->query(
            "SELECT *,  DATE_FORMAT(date_of_income, '%Y-%m-%d') as formatted_date
            FROM incomes
            WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        )->find();
    }

    public function getUserExpense(string $id)
    {
        return $this->db->query(
            "SELECT *,  DATE_FORMAT(date_of_expense, '%Y-%m-%d') as formatted_date
            FROM expenses
            WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        )->find();
    }

    public function updateIncome(array $formData, int $id)
    {
        $formattedDate = "{$formData['incomeDate']} 00:00:00";

        $this->db->query(
            "UPDATE incomes
             SET income_category_assigned_to_user_id = :income_category_assigned_to_user_id, 
                 amount = :amount, 
                 date_of_income = :date_of_income, 
                 income_comment = :income_comment 
             WHERE id = :id AND user_id = :user_id",
            [
                'user_id' => $_SESSION['user'],
                'income_category_assigned_to_user_id' => $formData['incomeCategory'],
                'amount' => $formData['incomeAmount'],
                'date_of_income' => $formattedDate,
                'income_comment' => $formData['incomeComment'],
                'id' => $id
            ]
        );
    }
    public function deleteIncome(int $id)
    {
        $this->db->query(
            "DELETE FROM incomes WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        );
    }

    public function updateExpense(array $formData, int $id)
    {
        $formattedDate = "{$formData['expenseDate']} 00:00:00";

        $this->db->query(
            "UPDATE expenses
             SET expense_category_assigned_to_user_id = :expense_category_assigned_to_user_id, 
                 amount = :amount, 
                 date_of_expense = :date_of_expense, 
                 expense_comment = :expense_comment 
             WHERE id = :id AND user_id = :user_id",
            [
                'user_id' => $_SESSION['user'],
                'expense_category_assigned_to_user_id' => $formData['expenseCategory'],
                'amount' => $formData['expenseAmount'],
                'date_of_expense' => $formattedDate,
                'expense_comment' => $formData['expenseComment'],
                'id' => $id
            ]
        );
    }
    public function deleteExpense(int $id)
    {
        $this->db->query(
            "DELETE FROM expenses WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        );
    }
}
