<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            $oldFormData = $_POST;

            $exludedFields = ['password', 'confirm-password'];
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($exludedFields)
            );

            $_SESSION['errors'] = $e->errors;
            $_SESSION['oldFormData'] = $formattedFormData;

            redirectTo($this->getRedirectPath());
        }
    }

    private function getRedirectPath(): string
    {
        $redirectTo = $_POST['redirect_to'] ?? '/';

        if (!is_string($redirectTo) || $redirectTo === '') {
            return '/';
        }

        // Only allow internal paths to prevent open redirects.
        if (str_starts_with($redirectTo, '/')) {
            return $redirectTo;
        }

        return '/';
    }
}
