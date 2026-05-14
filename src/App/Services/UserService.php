<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Services\CategoryService;

class UserService
{
    private CategoryService $categoryService;

    public function __construct(
        private Database $db,
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    public function isEmailTaken(string $email)
    {
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                'email' => $email
            ]
        )->count();

        if ($emailCount > 0) {
            throw new ValidationException(['email' => ['Email is already taken']]);
        }
    }

    public function updateEmail(string $email, int $userId)
    {
        $this->db->query(
            "UPDATE users SET email = :email WHERE id = :uid",
            ['email' => $email, 'uid' => $userId]
        );
    }

    public function create(array $formData)
    {

        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $this->db->query(
            "INSERT INTO users(email, username, password)
            VALUES(:email, :username, :password)",
            [
                'email' => $formData['email'],
                'username' => $formData['username'],
                'password' => $password
            ]
        );
        $userId = (int) $this->db->id();


        $this->categoryService->copyDefaultIncomeCategories($userId);
        $this->categoryService->copyDefaultExpenseCategories($userId);

        session_regenerate_id();

        $_SESSION['user'] = $userId;
    }

    public function login(array $formData)
    {
        $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                'email' => $formData['email']
            ]
        )->find();

        $passwordMatch = password_verify(
            $formData['password'],
            $user['password'] ?? ''
        );

        if (!$user || !$passwordMatch) {
            throw new ValidationException(['password' => ['Invalid credentials']]);
        }

        session_regenerate_id();

        $_SESSION['user'] = $user['id'];
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    public function getUsername(int $userId)
    {
        return  $this->db->query(
            "SELECT username FROM users WHERE id = :id",
            [
                'id' => $userId
            ]
        )->find();
    }

    public function getUserEmail(int $userId)
    {
        return $this->db->query(
            "SELECT email FROM users WHERE id = :id",
            [
                'id' => $userId
            ]
        )->find();
    }

    public function updateUsername(int $userId, string $newUsername)
    {
        $this->db->query(
            "UPDATE users SET username = :username WHERE id = :uid",
            ['username' => $newUsername, 'uid' => $userId]
        );
    }

    public function updatePassword(int $userId, string $newPassword)
    {
        $password = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->db->query(
            "UPDATE users SET password = :password WHERE id = :uid",
            ['password' => $password, 'uid' => $userId]
        );
    }

    public function deleteById($id)
    {
        $this->db->query("
        DELETE FROM users WHERE id = :id", [
            'id' => $id
        ]);
        $this->logout();
    }

    public function getFullUserData(int $userId): array
    {
        return [
            'user_info' => $this->db->query(
                "SELECT username, email, created_at FROM users WHERE id = :id",
                ['id' => $userId]
            )->find(),
            'transactions' => [
                'incomes' => $this->db->query(
                    "SELECT amount, date_of_income AS date, income_comment AS description
                 FROM incomes
                 WHERE user_id = :id",
                    ['id' => $userId]
                )->fetchAll(),
                'expenses' => $this->db->query(
                    "SELECT amount, date_of_expense AS date, expense_comment AS description
                 FROM expenses
                 WHERE user_id = :id",
                    ['id' => $userId]
                )->fetchAll(),
                'contributions' => $this->db->query(
                    "SELECT gc.amount, gc.contribution_date AS date, g.goal_name
                    FROM goal_contributions gc
                    JOIN goals g ON gc.goal_id = g.id
                    WHERE gc.user_id = :id",
                    ['id' => $userId]
                )->fetchAll(),
            ],
            'goals' => $this->db->query(
                "SELECT g.goal_name AS name,
                    g.amount_needed AS target_amount,
                    COALESCE(SUM(gc.amount), 0) AS current_amount,
                    g.created_at
             FROM goals g
             LEFT JOIN goal_contributions gc ON gc.goal_id = g.id
             WHERE g.user_id = :id
             GROUP BY g.id",
                ['id' => $userId]
            )->fetchAll()
        ];
    }
}
