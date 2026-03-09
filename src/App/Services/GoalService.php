<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class GoalService
{
    public function __construct(private Database $db) {}

    public function createNewGoal(array $formData): void
    {
        $goalName = $this->normalizeGoalName($formData['goalName']);
        $userId = $_SESSION['user'];

        $deadline = null;

        if (!empty($formData['goalDate'])) {
            $deadline = "{$formData['goalDate']} 00:00:00";
        }

        $this->db->query(
            "INSERT INTO goals (
            user_id,
            goal_name,
            amount_needed,
            goal_description,
            deadline
        ) VALUES (
            :user_id,
            :goal_name,
            :amount_needed,
            :goal_description,
            :deadline
        )",
            [
                'user_id' => $userId,
                'goal_name' => $goalName,
                'amount_needed' => $formData['goalAmount'],
                'goal_description' => $formData['goalDescription'] ?? null,
                'deadline' => $deadline
            ]
        );
    }

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

    private function normalizeGoalName(string $name): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($name));
        $lower = mb_strtolower($clean, 'UTF-8');

        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');
    }
}
