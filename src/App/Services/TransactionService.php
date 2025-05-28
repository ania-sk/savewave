<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{
    public function __construct(private Database $db) {}

    public function createIncome(array $formData)
    {
        $formattedDate = "{$formData['incomeDate']} 00:00:00";
        $this->db->query(
            "INSERT INTO incomes(
                user_id, 
                income_category_assigned_to_user_id, 
                amount, 
                date_of_income, 
                income_comment
            ) VALUES(
                :user_id, 
                :income_category_assigned_to_user_id, 
                :amount, 
                :date_of_income, 
                :income_comment
            )",
            [
                'user_id' => $_SESSION['user'],
                'income_category_assigned_to_user_id' => $formData['incomeCategory'],
                'amount' => $formData['incomeAmount'],
                'date_of_income' => $formattedDate,
                'income_comment' => $formData['incomeComment']
            ]
        );
    }
}
