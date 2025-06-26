<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $view) {}
    public function process(callable $next)
    {
        $this->view->addGlobal('errors', $_SESSION['errors'] ?? []);

        unset($_SESSION['errors']);

        $this->view->addGlobal('oldFormData', $_SESSION['oldFormData'] ?? []);

        unset($_SESSION['oldFormData']);

        $this->view->addGlobal('success', $_SESSION['success'] ?? null);
        unset($_SESSION['success']);

        $this->view->addGlobal('activeForm', $_SESSION['activeForm'] ?? null);

        unset($_SESSION['activeForm']);

        $this->view->addGlobal('newCategoryName', $_SESSION['newCategoryName'] ?? []);

        unset($_SESSION['newCategoryName']);

        $this->view->addGlobal('categoryToEdit', $_SESSION['categoryToEdit'] ?? null);

        unset($_SESSION['categoryToEdit']);

        $next();
    }
}
