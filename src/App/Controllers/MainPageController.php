<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class MainPageController
{
    public function __construct(private TemplateEngine $view) {}

    public function mainPage()
    {
        echo $this->view->render("/mainPage.php", [
            'title' => 'Budget Application',
            'cssLink' => 'mainPage.css'
        ]);
    }
}
