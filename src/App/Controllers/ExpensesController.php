<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class ExpensesController
{
    public function __construct(private TemplateEngine $view) {}

    public function expenses()
    {
        echo $this->view->render("/expenses.php", [
            'title' => 'Expenses',
            'cssLink' => 'expenses.css',
            'cssLink2' => 'mainPage.css',
            'jsLink' => 'expenses.js'
        ]);
    }
}
