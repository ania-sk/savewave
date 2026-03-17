<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class ChangeContributionAmountRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        $newAmount = (float)$data[$field];

        $oldAmount = (float)$data['oldContributionAmount'];

        $balance = (float)$params[0];

        $difference = $newAmount - $oldAmount;

        return $difference <= $balance;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "You don't have enough funds to increase this contribution.";
    }
}
