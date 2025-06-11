<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class UniqueCategoryRule implements RuleInterface
{
    protected array $existingCategories;

    public function __construct(array $existingCategories)
    {
        $this->existingCategories = $existingCategories;
    }

    public function validate(array $data, string $field, array $params): bool
    {
        $newCategory = trim($data[$field] ?? '');

        if ($newCategory === '') {
            return true;
        }

        foreach ($this->existingCategories as $category) {
            if (isset($category['name']) && strcasecmp($newCategory, $category['name']) === 0) {
                return false;
            }
        }

        return true;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Enter another category name.";
    }
}
