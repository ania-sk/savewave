<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db, private GoalService $goalService) {}

    public function createIncome(array $formData, int $userId)
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
                'user_id' => $userId,
                'income_category_assigned_to_user_id' => $formData['incomeCategory'],
                'amount' => $formData['incomeAmount'],
                'date_of_income' => $formattedDate,
                'income_comment' => $formData['incomeComment']
            ]
        );
    }

    public function createExpense(array $formData, int $userId)
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
                'user_id' => $userId,
                'expense_category_assigned_to_user_id' => $formData['expenseCategory'],
                'amount' => $formData['expenseAmount'],
                'date_of_expense' => $formattedDate,
                'expense_comment' => $formData['expenseComment']
            ]
        );
    }

    public function getUserIncomes(int $userId)
    {
        $incomes = $this->db->query(
            "SELECT 
                i.id,
                i.amount, 
                i.income_comment, 
                DATE_FORMAT(i.date_of_income, '%Y-%m-%d') as formatted_date,
                c.name,
                c.id as categoryId,
                c.is_active as active
            FROM incomes AS i
            JOIN incomes_category_assigned_to_users AS c 
            ON i.income_category_assigned_to_user_id = c.id
            WHERE i.user_id = :user_id
            ORDER BY i.date_of_income DESC",
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
             c.name,
             c.id as categoryId,
             c.is_active as active
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

    public function getUserExpenses(int $userId)
    {
        $expenses = $this->db->query(
            "SELECT 
                e.id,
                e.amount, 
                e.expense_comment, 
                DATE_FORMAT(e.date_of_expense, '%Y-%m-%d') as formatted_date,
                c.name,
                c.monthly_limit,
                c.id as categoryId,
                c.is_active as active
            FROM expenses AS e
            JOIN expenses_category_assigned_to_users AS c 
            ON e.expense_category_assigned_to_user_id = c.id
            WHERE e.user_id = :user_id
            ORDER BY e.date_of_expense DESC",
            ['user_id' => $userId]
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
             c.name,
             c.monthly_limit,
             c.id as categoryId,
             c.is_active as active
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

    public function updateIncome(array $formData, int $id, int $userId)
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
                'user_id' => $userId,
                'income_category_assigned_to_user_id' => $formData['incomeCategory'],
                'amount' => $formData['incomeAmount'],
                'date_of_income' => $formattedDate,
                'income_comment' => $formData['incomeComment'],
                'id' => $id
            ]
        );
    }
    public function deleteIncome(int $id, int $userId)
    {
        $this->db->query(
            "DELETE FROM incomes WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $userId
            ]
        );
    }

    public function updateExpense(array $formData, int $id, int $userId)
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
                'user_id' => $userId,
                'expense_category_assigned_to_user_id' => $formData['expenseCategory'],
                'amount' => $formData['expenseAmount'],
                'date_of_expense' => $formattedDate,
                'expense_comment' => $formData['expenseComment'],
                'id' => $id
            ]
        );
    }
    public function deleteExpense(int $id, int $userId)
    {
        $this->db->query(
            "DELETE FROM expenses WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $userId
            ]
        );
    }

    public function getTotalIncome(int $userId, ?string $startDate = null, ?string $endDate = null): float
    {
        if (!empty($startDate) && !empty($endDate)) {
            $result = $this->db->query(
                "SELECT COALESCE(SUM(i.amount), 0) AS total
                 FROM incomes AS i
                 WHERE i.user_id = :user_id
                   AND i.date_of_income BETWEEN :start AND :end",
                [
                    'user_id' => $userId,
                    'start' => $startDate . ' 00:00:00',
                    'end' => $endDate . ' 23:59:59'
                ]
            )->find();
        } else {
            $result = $this->db->query(
                "SELECT COALESCE(SUM(i.amount), 0) AS total
                 FROM incomes AS i
                 WHERE i.user_id = :user_id",
                ['user_id' => $userId]
            )->find();
        }

        return (float) ($result['total'] ?? 0);
    }

    public function getTotalExpense(int $userId, ?string $startDate = null, ?string $endDate = null): float
    {
        if (!empty($startDate) && !empty($endDate)) {
            $result = $this->db->query(
                "SELECT COALESCE(SUM(e.amount), 0) AS total
                 FROM expenses AS e
                 WHERE e.user_id = :user_id
                   AND e.date_of_expense BETWEEN :start AND :end",
                [
                    'user_id' => $userId,
                    'start' => $startDate . ' 00:00:00',
                    'end' => $endDate . ' 23:59:59'
                ]
            )->find();
        } else {
            $result = $this->db->query(
                "SELECT COALESCE(SUM(e.amount), 0) AS total
                 FROM expenses AS e
                 WHERE e.user_id = :user_id",
                ['user_id' => $userId]
            )->find();
        }

        return (float) ($result['total'] ?? 0);
    }

    public function getBalance(int $userId, ?string $startDate = null, ?string $endDate = null): array
    {
        if (!empty($startDate) && !empty($endDate)) {

            $dtStart = $startDate . ' 00:00:00';
            $dtEnd   = $endDate   . ' 23:59:59';

            $expenses = $this->getUserExpensesByDateRange($userId, $dtStart, $dtEnd);
            $expensesSumsByCat = $this->getExpenseSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);

            $incomes = $this->getUserIncomesByDateRange($userId, $dtStart, $dtEnd);
            $incomesSumsByCat = $this->getIncomeSumsByCategoryAndDateRange($userId, $dtStart, $dtEnd);

            $contributions = $this->goalService->getUserContributionsByDateRange($userId, $dtStart, $dtEnd);
            $contributonsSumsByGoal = $this->goalService->getContributionSumsByGoalAndDateRange($userId, $startDate, $endDate);

            $totalExpense = $this->getTotalExpense($userId, $startDate, $endDate);
            $totalIncome = $this->getTotalIncome($userId, $startDate, $endDate);
        } else {
            $expenses = $this->getUserExpenses($userId);
            $expensesSumsByCat = $this->getExpenseSumsByCategory($userId);

            $incomes = $this->getUserIncomes($userId);
            $incomesSumsByCat = $this->getIncomeSumsByCategory($userId);

            $contributions = $this->goalService->getUserContributions($userId);
            $contributonsSumsByGoal = $this->goalService->getContributionSumsByGoal($userId);

            $totalExpense = $this->getTotalExpense($userId);
            $totalIncome = $this->getTotalIncome($userId);
        }

        $totalContributions = array_sum(array_column($contributions, 'amount'));
        $balance = $totalIncome - $totalExpense - $totalContributions;

        return [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'contributions' => $contributions,
            'totalExpense' => $totalExpense,
            'totalIncome' => $totalIncome,
            'totalContributions' => $totalContributions,
            'expensesSumsByCat' => $expensesSumsByCat,
            'incomesSumsByCat' => $incomesSumsByCat,
            'contributionsSumsByGoal' => $contributonsSumsByGoal,
            'balance' => $balance
        ];
    }

    public function getUserIncomesPage(int $userId, int $limit, int $offset): array
    {
        return $this->db->query(
            "SELECT
                i.id,
                i.amount,
                i.income_comment,
                DATE_FORMAT(i.date_of_income, '%Y-%m-%d') AS formatted_date,
                c.name,
                c.id AS categoryId,
                c.is_active AS active
             FROM incomes AS i
             JOIN incomes_category_assigned_to_users AS c
               ON i.income_category_assigned_to_user_id = c.id
             WHERE i.user_id = :user_id
             ORDER BY i.date_of_income DESC
             LIMIT {$limit} OFFSET {$offset}",
            [
                'user_id' => $userId
            ]
        )->fetchAll();
    }

    public function countUserIncomes(int $userId): int
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM incomes
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        )->find();

        return (int) ($result['total'] ?? 0);
    }

    public function getUserIncomesPageByDateRange(int $userId, string $startDate, string $endDate, int $limit, int $offset): array
    {
        return $this->db->query(
            "SELECT
                i.id,
                i.amount,
                i.income_comment,
                DATE_FORMAT(i.date_of_income, '%Y-%m-%d') AS formatted_date,
                c.name,
                c.id AS categoryId,
                c.is_active AS active
             FROM incomes AS i
             JOIN incomes_category_assigned_to_users AS c
               ON i.income_category_assigned_to_user_id = c.id
             WHERE i.user_id = :user_id
               AND i.date_of_income BETWEEN :start AND :end
             ORDER BY i.date_of_income DESC
             LIMIT {$limit} OFFSET {$offset}",
            [
                'user_id' => $userId,
                'start' => $startDate,
                'end' => $endDate
            ]
        )->fetchAll();
    }

    public function countUserIncomesByDateRange(int $userId, string $startDate, string $endDate): int
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM incomes
             WHERE user_id = :user_id
               AND date_of_income BETWEEN :start AND :end",
            [
                'user_id' => $userId,
                'start' => $startDate,
                'end' => $endDate
            ]
        )->find();

        return (int) ($result['total'] ?? 0);
    }

    public function getUserExpensesPage(int $userId, int $limit, int $offset): array
    {
        return $this->db->query(
            "SELECT
                e.id,
                e.amount,
                e.expense_comment,
                DATE_FORMAT(e.date_of_expense, '%Y-%m-%d') AS formatted_date,
                c.name,
                c.monthly_limit,
                c.id AS categoryId,
                c.is_active AS active
             FROM expenses AS e
             JOIN expenses_category_assigned_to_users AS c
               ON e.expense_category_assigned_to_user_id = c.id
             WHERE e.user_id = :user_id
             ORDER BY e.date_of_expense DESC
             LIMIT {$limit} OFFSET {$offset}",
            [
                'user_id' => $userId
            ]
        )->fetchAll();
    }

    public function countUserExpenses(int $userId): int
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM expenses
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        )->find();

        return (int) ($result['total'] ?? 0);
    }

    public function getUserExpensesPageByDateRange(int $userId, string $startDate, string $endDate, int $limit, int $offset): array
    {
        return $this->db->query(
            "SELECT
                e.id,
                e.amount,
                e.expense_comment,
                DATE_FORMAT(e.date_of_expense, '%Y-%m-%d') AS formatted_date,
                c.name,
                c.monthly_limit,
                c.id AS categoryId,
                c.is_active AS active
             FROM expenses AS e
             JOIN expenses_category_assigned_to_users AS c
               ON e.expense_category_assigned_to_user_id = c.id
             WHERE e.user_id = :user_id
               AND e.date_of_expense BETWEEN :start AND :end
             ORDER BY e.date_of_expense DESC
             LIMIT {$limit} OFFSET {$offset}",
            [
                'user_id' => $userId,
                'start' => $startDate,
                'end' => $endDate
            ]
        )->fetchAll();
    }

    public function countUserExpensesByDateRange(int $userId, string $startDate, string $endDate): int
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total
             FROM expenses
             WHERE user_id = :user_id
               AND date_of_expense BETWEEN :start AND :end",
            [
                'user_id' => $userId,
                'start' => $startDate,
                'end' => $endDate
            ]
        )->find();

        return (int) ($result['total'] ?? 0);
    }

    public function buildTransactionHistory(array $incomes, array $expenses, array $contributions): array
    {
        $transactions = [];

        foreach ($incomes as $income) {
            $transactions[] = [
                'type' => 'Income',
                'category' => $income['name'],
                'date' => $income['formatted_date'],
                'amount' => $income['amount']
            ];
        }

        foreach ($expenses as $expense) {
            $transactions[] = [
                'type' => 'Expense',
                'category' => $expense['name'],
                'date' => $expense['formatted_date'],
                'amount' => $expense['amount'],
                'monthly_limit' => $expense['monthly_limit']
            ];
        }

        foreach ($contributions as $contribution) {
            $transactions[] = [
                'type' => 'Contribution',
                'category' => $contribution['goal_name'],
                'date' => $contribution['formatted_date'],
                'amount' => $contribution['amount']
            ];
        }

        usort($transactions, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));

        return $transactions;
    }
}
