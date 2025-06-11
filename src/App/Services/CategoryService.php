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

    public function getUserIncomeCategories(int $userId): array
    {
        $this->db->query(
            "SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id",
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
            "SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $this->db->fetchAll();
    }

    public function createUserCategory(array $formData)
    {
        $newCategoryName = trim($formData['newCategoryName'] ?? '');
        $userId =  $_SESSION['user'];

        $categoriesAssignedToUser = $this->getUserIncomeCategories($userId);

        foreach ($categoriesAssignedToUser as $category) {
            if (isset($category['name']) && strcasecmp($newCategoryName, $category['name']) === 0) {
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
}
