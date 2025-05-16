<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class IncomesController
{
    public function __construct(private TemplateEngine $view) {}

    public function incomes()
    {
        echo $this->view->render("/incomes.php", [
            'title' => 'Incomes',
            'cssLink' => 'incomes.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'incomes.js'
        ]);
    }
}
