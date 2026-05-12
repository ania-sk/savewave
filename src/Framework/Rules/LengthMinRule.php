<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class LengthMinRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        if (empty($params[0])) {
            throw new InvalidArgumentException('Minimum length not specified');
        }

        $length = (int) $params[0];

        if (!isset($data[$field]) || $data[$field] === '') {
            return false;
        }

        return mb_strlen((string) $data[$field], 'UTF-8') >= $length;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Must be at least {$params[0]} characters long";
    }
}
