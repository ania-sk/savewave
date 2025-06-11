<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Database, Container};
use App\Config\Paths;
use App\Services\{
    ValidatorService,
    UserService,
    TransactionService,
    CategoryService
};

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEW),
    ValidatorService::class => function (Container $container) {
        $categoryService = $container->get(CategoryService::class);
        return new ValidatorService($categoryService);
    },
    Database::class => fn() => new Database($_ENV['DB_DRIVER'], [
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME']
    ], $_ENV['DB_USER'], $_ENV['DB_PASS']),
    UserService::class => function (Container $container) {
        $db = $container->get(Database::class);
        $categoryService = $container->get(CategoryService::class);

        return new UserService($db, $categoryService);
    },
    TransactionService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new TransactionService($db);
    },
    CategoryService::class => function (Container $container) {
        $db = $container->get(Database::class);

        return new CategoryService($db);
    }
];
