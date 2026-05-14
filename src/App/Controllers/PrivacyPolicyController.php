<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;


class PrivacyPolicyController
{
    public function __construct(
        private TemplateEngine $view

    ) {}

    public function privacyPolicy()
    {
        echo $this->view->render(
            "/privacyPolicy.php",
            [
                'title' => 'Privacy Policy',
                'cssLink' => 'privacy-policy.css',
                'cssLink2' => 'main.css'
            ]
        );
    }
}
