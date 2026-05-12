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

        if (!in_array($requestMethod, $validMethods, true)) {
            $next();
            return;
        }

        if (
            !isset($_SESSION['token'], $_POST['token']) ||
            !is_string($_POST['token']) ||
            !hash_equals($_SESSION['token'], $_POST['token'])
        ) {
            http_response_code(403);
            redirectTo('/');
        }

        unset($_SESSION['token']);

        $next();
    }
}
