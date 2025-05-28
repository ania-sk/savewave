<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db) {}

    public function isEmailTaken(string $email)
    {
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                'email' => $email
            ]
        )->count();

        if ($emailCount > 0) {
            throw new ValidationException(['email' => 'Email is already taken']);
        }
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

        $this->copyDefaultCategories($userId);

        session_regenerate_id();

        $_SESSION['user'] = $this->db->id();
    }

    private function copyDefaultCategories(int $userId)
    {
        $this->db->query(
            "INSERT INTO incomes_category_assigned_to_users (user_id, name)
             SELECT :user_id, name FROM incomes_category_default",
            ['user_id' => $userId]
        );
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
}
