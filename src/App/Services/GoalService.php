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
            g.id,
            g.goal_name,
            g.amount_needed,
            g.goal_description,
            g.deadline,
            COALESCE(SUM(c.amount),0) AS amount_saved,
            ROUND(COALESCE(SUM(c.amount),0) / g.amount_needed * 100 , 2) AS progress         
            FROM goals g
            LEFT JOIN goal_contributions c
            ON g.id = c.goal_id
            WHERE g.user_id = :user_id
            GROUP BY g.id",

            ['user_id' => $userId]

        )->fetchAll();

        return $goals;
    }

    public function getGoalById($goalId)
    {
        return $this->db->query(
            "SELECT id, goal_name, goal_description, amount_needed, deadline
         FROM goals
         WHERE id = :goalId AND user_id = :userId",
            [
                "goalId" => $goalId,
                "userId" => $_SESSION['user']
            ]
        )->find();
    }

    public function updateGoal(array $formData)
    {
        $this->db->query(
            "UPDATE goals
            SET goal_name = :goal_name, 
            goal_description = :goal_description,
             amount_needed = :amount_needed, 
             deadline = :deadline
             WHERE id = :id AND user_id = :user_id",
            [
                'user_id' => $_SESSION['user'],
                'goal_name' => $formData['goalName'],
                'goal_description' => $formData['goalDescription'],
                'amount_needed' => $formData['goalAmount'],
                'deadline' => $formData['goalDate'],
                'id' => $formData['goalId']
            ]
        );
    }

    public function deleteGoal($goalId)
    {
        $this->db->query(
            "DELETE FROM goals WHERE id = :id AND user_id = :user_id",
            [
                'id' => $goalId,
                'user_id' => $_SESSION['user']
            ]
        );
    }

    public function getUserContributions(int $userId)
    {
        return $contributions = $this->db->query(
            "SELECT gc.id,
            gc.goal_id,
                gc.amount,
                gc.contribution_date,
                g.goal_name
         FROM goal_contributions gc
         JOIN goals g ON g.id = gc.goal_id
         WHERE gc.user_id = :userId",
            [
                "userId" => $userId
            ]
        )->fetchAll();
    }

    public function store(array $formData)
    {
        $this->db->query(
            "INSERT INTO goal_contributions
            (user_id, goal_id, amount)
            VALUES (:user_id, :goal_id, :amount)",
            [
                'user_id' => $_SESSION['user'],
                'goal_id' => $formData['goalId'],
                'amount' => $formData['amount']
            ]
        );
    }

    public function deleteContribution(int $contributionId)
    {
        $this->db->query(
            "DELETE FROM goal_contributions WHERE id = :id AND user_id = :user_id",
            [
                'id' => $contributionId,
                'user_id' => $_SESSION['user']
            ]
        );
    }

    // public function getUserGoal(string $id)
    // {
    //     return $this->db->query(
    //         "SELECT *, DATE_FORMAT(deadline, '%Y-%m-%d') as formatted_date
    //         FROM goals
    //         WHERE id = :id AND user_id = :user_id",
    //         [
    //             'id' => $id,
    //             'user_id' => $_SESSION['user']
    //         ]
    //     )->find();
    // }

    // public function updateGoal(array $formData, int $id)
    // {
    //     $formattedDate = "{$formData['deadline']} 00:00:00";

    //     $this->db->query(
    //         "UPDATE goals 
    //         SET goal_name = :goal_name,
    //         amount_needed = :amount_needed,
    //         goal_description = :goal_description,
    //         deadline = :deadline
    //         WHERE id = :id AND user_id = :user_id",
    //         [
    //             'user_id' => $_SESSION['user'],
    //             'id' => $id,
    //             'goal_name' => $formData['goalName'],
    //             'amount_needed' => $formData['goalAmount'],
    //             'goal_description' => $formData['goalDescription'],
    //             'deadline' => $formattedDate
    //         ]
    //     );
    // }

    private function normalizeGoalName(string $name): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($name));
        $lower = mb_strtolower($clean, 'UTF-8');

        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');
    }
}
