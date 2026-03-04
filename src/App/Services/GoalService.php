<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class GoalService
{
    public function __construct(private Database $db) {}

    public function createGoal(array $formData) {}
    public function getUserGoals($userId)
    {
        $goals = $this->db->query(
            "SELECT
            id,
            goal_name,
            amount_needed,
            goal_description,
            deadline,
            DATE_FORMAT(created_at, '%Y-%m-%d') as formatted_date
            FROM goals
            WHERE user_id = :user_id",
            ['user_id' => $userId]

        )->fetchAll();

        return $goals;
    }
}
