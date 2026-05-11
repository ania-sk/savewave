<?php

include __DIR__ . "/src/Framework/Database.php";

use Framework\Database;

// FIXME - AI CR - [C1 CRITICAL][Bezpieczeństwo] Hardcoded credentials bazy danych (root bez hasła). Użyj .env i phpdotenv jak w bootstrap.php.
$db = new Database('mysql', [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'savewave'
], 'root', '');

$sqlFile = file_get_contents("./database.sql");

$db->query($sqlFile);
