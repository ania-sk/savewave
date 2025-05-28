<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class CategoryService
{

    public function __construct(private Database $db) {}

    public function copyDefaultCategories(int $userId)
    {
        $this->db->query(
            "INSERT INTO incomes_category_assigned_to_users (user_id, name)
             SELECT :user_id, name FROM incomes_category_default",
            ['user_id' => $userId]
        );
    }
}
