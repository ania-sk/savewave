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
        unset($_SESSION['user']);

        session_regenerate_id();
    }

    public function getUsername(int $userId)
    {
        return $username = $this->db->query(
            "SELECT username FROM users WHERE id = :id",
            [
                'id' => $userId
            ]
        )->find();
    }

    public function getUserEmail(int $userId)
    {
        return $email = $this->db->query(
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
}
