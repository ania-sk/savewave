<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class LessThanBalanceRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        $amount = (float)$data[$field];

        $balance = (float)$params[0];

        return $amount <= $balance;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        $balance = number_format((float)$params[0], 2);

        return "This amount exceeds your available balance. \nYou can contribute up to {$balance}.";
    }
}
