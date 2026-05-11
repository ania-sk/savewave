<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        $validMethods = ['POST', 'PATCH', 'DELETE'];

        if (!in_array($requestMethod, $validMethods)) {
            $next();
            return;
        }

        // FIXME - AI CR - [W1 WARNING][Bezpieczeństwo] Porównanie CSRF tokena nie jest timing-safe (użyj hash_equals()), brak sprawdzenia istnienia $_SESSION['token'] i $_POST['token']. Rozważ rotację tokena (odkomentuj unset poniżej).
        if ($_SESSION['token'] !== $_POST['token']) {
            redirectTo('/');
        }

        // unset($_SESSION['token']);

        $next();
    }
}
