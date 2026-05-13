<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class CategoryService
{

    public function __construct(private Database $db) {}

    public function copyDefaultIncomeCategories(int $userId)
    {
        $this->db->query(
            "INSERT INTO incomes_category_assigned_to_users (user_id, name)
             SELECT :user_id, name FROM incomes_category_default",
            ['user_id' => $userId]
        );
    }

    public function getUserActiveIncomeCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name, monthly_limit
             FROM incomes_category_assigned_to_users 
             WHERE user_id = :user_id
             AND is_active = 1",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function getUserAllIncomeCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name, is_active
             FROM incomes_category_assigned_to_users 
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function copyDefaultExpenseCategories(int $userId)
    {
        $this->db->query(
            "INSERT INTO expenses_category_assigned_to_users (user_id, name)
             SELECT :user_id, name FROM expenses_category_default",
            ['user_id' => $userId]
        );
    }

    public function getUserActiveExpenseCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name, monthly_limit
             FROM expenses_category_assigned_to_users 
             WHERE user_id = :user_id
             AND is_active = 1",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function getUserAllExpenseCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name 
             FROM expenses_category_assigned_to_users 
             WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function getExpenseCategoryIdByName(int $userId, string $categoryName): ?int
    {
        $category = $this->db->query(
            "SELECT id 
            FROM expenses_category_assigned_to_users 
            WHERE user_id = :user_id 
            AND LOWER(name) = LOWER(:name)",
            [
                'user_id' => $userId,
                'name' => $categoryName
            ]
        )->find();

        return $category ? (int) $category['id'] : 0;
    }

    public function createUserIncomeCategory(array $formData, int $userId)
    {
        $newCategoryName = $this->normalizeCategoryName($formData['newCategoryName'] ?? '');

        // Check if category already exists (case-insensitive)
        $existingCategory = $this->db->query(
            "SELECT id, is_active
             FROM incomes_category_assigned_to_users
             WHERE user_id = :user_id
               AND LOWER(name) = LOWER(:name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        )->find();

        if ($existingCategory) {
            if ((int) $existingCategory['is_active'] === 0) {
                $this->db->query(
                    "UPDATE incomes_category_assigned_to_users
                    SET is_active = 1
                    WHERE id = :id
                    AND user_id = :uid",
                    [
                        'id' => $existingCategory['id'],
                        'uid' => $userId
                    ]
                );
            }
            return $existingCategory['id'];
        }

        $this->db->query(
            "INSERT INTO incomes_category_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        );

        return $this->db->id();
    }

    public function createUserExpenseCategory(array $formData, int $userId)
    {
        $newCategoryName = $this->normalizeCategoryName($formData['newCategoryName'] ?? '');

        // Check if category already exists (case-insensitive)
        $existingCategory = $this->db->query(
            "SELECT id, is_active
             FROM expenses_category_assigned_to_users
             WHERE user_id = :user_id
               AND LOWER(name) = LOWER(:name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        )->find();

        if ($existingCategory) {
            if ((int) $existingCategory['is_active'] === 0) {
                $this->db->query(
                    "UPDATE expenses_category_assigned_to_users
                    SET is_active = 1
                    WHERE id = :id
                    AND user_id = :uid",
                    [
                        'id' => $existingCategory['id'],
                        'uid' => $userId
                    ]
                );
            }
            return $existingCategory['id'];
        }

        $this->db->query(
            "INSERT INTO expenses_category_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        );

        return $this->db->id();
    }

    public function getUserCategoryById(int $id, int $userId): ?array
    {

        $category = $this->db->query(
            "SELECT * FROM incomes_category_assigned_to_users WHERE id = :id AND user_id = :uid",
            ['id' => $id, 'uid' => $userId]
        )->find();

        if ($category) {
            $category['type'] = 'income';
            return $category;
        }

        $category = $this->db->query(
            "SELECT * FROM expenses_category_assigned_to_users WHERE id = :id AND user_id = :uid",
            ['id' => $id, 'uid' => $userId]
        )->find();

        if ($category) {
            $category['type'] = 'expense';
            return $category;
        }

        return null;
    }

    public function updateUserIncomeCategory(int $id, string $name, int $userId): void
    {
        $name = $this->normalizeCategoryName($name);
        $this->db->query(
            "UPDATE incomes_category_assigned_to_users SET name = :name WHERE id = :id AND user_id = :uid",
            ['name' => $name, 'id' => $id, 'uid' => $userId]
        );
    }

    public function updateUserExpenseCategory(int $id, string $name, int $userId): void
    {
        $name = $this->normalizeCategoryName($name);
        $this->db->query(
            "UPDATE expenses_category_assigned_to_users SET name = :name WHERE id = :id AND user_id = :uid",
            ['name' => $name, 'id' => $id, 'uid' => $userId]
        );
    }

    public function deactivateCategory(int $id, string $type, int $userId): void
    {
        $table = $this->resolveCategoryTable($type);

        $this->db->query(
            "UPDATE {$table} 
          SET is_active = 0 
          WHERE id = :id 
          AND user_id = :uid",
            [
                'id'  => $id,
                'uid' => $userId
            ]
        );
    }

    public function updateCategoryLimit(int $id, float $limit, int $userId): void
    {
        $this->db->query(
            "UPDATE expenses_category_assigned_to_users
            SET monthly_limit = :limit 
            WHERE id = :id
            AND user_id = :uid",
            [
                'limit' => $limit,
                'id' => $id,
                'uid' => $userId
            ]

        );
    }

    private function resolveCategoryTable(string $type): string
    {
        return match ($type) {
            'income' => 'incomes_category_assigned_to_users',
            'expense' => 'expenses_category_assigned_to_users',
            default => throw new \InvalidArgumentException("Invalid category type: {$type}"),
        };
    }

    public function getCategoryLimit(int $categoryId, int $userId): ?float
    {
        $result = $this->db->query(
            "SELECT monthly_limit FROM expenses_category_assigned_to_users WHERE id = :id AND user_id = :user_id",
            [
                'id' => $categoryId,
                'user_id' => $userId
            ]
        )->find();

        return $result && $result['monthly_limit'] !== null ? (float)$result['monthly_limit'] : null;
    }

    public function getMonthlyCategoryTotalExpense(int $userId, int $categoryId, ?int $excludeExpenseId = null): float
    {
        $currentMonth = date('Y-m-01 00:00:00');
        $nextMonth = date('Y-m-01 00:00:00', strtotime('+1 month'));

        $query = "SELECT COALESCE(SUM(amount), 0) as total
                FROM expenses
                WHERE user_id = :user_id
                 AND expense_category_assigned_to_user_id = :category_id
                 AND date_of_expense >= :start_date
                 AND date_of_expense < :end_date";

        $params = [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'start_date' => $currentMonth,
            'end_date' => $nextMonth
        ];

        if ($excludeExpenseId !== null) {
            $query .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeExpenseId;
        }

        $result = $this->db->query($query, $params)->find();
        return (float)($result['total'] ?? 0);
    }


    private function normalizeCategoryName(string $name): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($name));
        $lower = mb_strtolower($clean, 'UTF-8');

        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');
    }
}
