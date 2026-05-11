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

            // FIXME - AI CR - [C4 CRITICAL][Bezpieczeństwo] Open Redirect — HTTP_REFERER jest nagłówkiem kontrolowanym przez klienta. Atakujący może przekierować na złośliwą stronę. Waliduj referer (np. sprawdź czy host się zgadza) lub użyj stałej ścieżki fallback.
            $referer = $_SERVER['HTTP_REFERER'];
            redirectTo($referer);
        }
    }
}
