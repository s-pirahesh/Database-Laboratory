<?php
// includes/database.php

require_once 'db_config.php';

$pdo = null;

try {
    $dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
} catch (PDOException $e) {
    die("خطا در اتصال به پایگاه داده: " . $e->getMessage());
}
?>