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
            "SELECT id, name 
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

    public function getUserExpenseCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name 
             FROM expenses_category_assigned_to_users 
             WHERE user_id = :user_id
             AND is_active = 1",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function createUserIncomeCategory(array $formData)
    {
        $newCategoryName = trim($formData['newCategoryName'] ?? '');
        $userId =  $_SESSION['user'];

        $categoriesAssignedToUser = $this->getUserAllIncomeCategories($userId);

        foreach ($categoriesAssignedToUser as $category) {
            if (isset($category['name']) && strcasecmp($newCategoryName, $category['name']) === 0) {
                if ((int) $category['is_active'] === 0) {
                    $this->db->query(
                        "UPDATE incomes_category_assigned_to_users
                        SET is_active = 1
                        WHERE id = :id 
                        AND user_id = :uid",
                        [
                            'id' => $category['id'],
                            'uid' => $userId
                        ]
                    );
                }
                return;
            }
        }

        $this->db->query(
            "INSERT INTO incomes_category_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        );
    }

    public function createUserExpenseCategory(array $formData)
    {
        $newCategoryName = trim($formData['newCategoryName'] ?? '');
        $userId =  $_SESSION['user'];

        $categoriesAssignedToUser = $this->getUserExpenseCategories($userId);

        foreach ($categoriesAssignedToUser as $category) {
            if (isset($category['name']) && strcasecmp($newCategoryName, $category['name']) === 0) {
                return;
            }
        }

        $this->db->query(
            "INSERT INTO expenses_category_assigned_to_users (user_id, name)
            VALUES (:user_id, :name)",
            [
                'user_id' => $userId,
                'name' => $newCategoryName
            ]
        );
    }

    public function getUserCategoryById(int $id): ?array
    {
        $userId = $_SESSION['user'] ?? null;

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

    public function updateUserIncomeCategory(int $id, string $name): void
    {
        $this->db->query(
            "UPDATE incomes_category_assigned_to_users SET name = :name WHERE id = :id AND user_id = :uid",
            ['name' => $name, 'id' => $id, 'uid' => $_SESSION['user']]
        );
    }

    public function updateUserExpenseCategory(int $id, string $name): void
    {
        $this->db->query(
            "UPDATE expenses_category_assigned_to_users SET name = :name WHERE id = :id AND user_id = :uid",
            ['name' => $name, 'id' => $id, 'uid' => $_SESSION['user']]
        );
    }

    public function deactivateCategory(int $id, string $type): void
    {
        $table = $type === 'income'
            ? 'incomes_category_assigned_to_users'
            : 'expenses_category_assigned_to_users';

        $this->db->query(
            "UPDATE {$table} 
          SET is_active = 0 
          WHERE id = :id 
          AND user_id = :uid",
            [
                'id'  => $id,
                'uid' => $_SESSION['user']
            ]
        );
    }
}
