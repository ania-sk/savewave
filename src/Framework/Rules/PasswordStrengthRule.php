<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class PasswordStrengthRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        if (!isset($data[$field]) || $data[$field] === '') {
            return false;
        }

        $password = (string) $data[$field];

        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasDigit = preg_match('/\d/', $password);
        $hasSymbol = preg_match('/[^a-zA-Z\d]/', $password);

        return (bool) ($hasUpper && $hasLower && $hasDigit && $hasSymbol);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return 'Password must contain at least one uppercase letter [A-Z], one lowercase letter [a-z], one digit [1-9], and one symbol.';
    }
}
