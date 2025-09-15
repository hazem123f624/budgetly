<?php

// Simple DB creator using Laravel's .env via Dotenv

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Load .env from the Laravel app root
if (class_exists(\Dotenv\Dotenv::class)) {
    \Dotenv\Dotenv::createImmutable(dirname(__DIR__))->load();
}

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';
$dbName = $_ENV['DB_DATABASE'] ?? 'Budgetly';

$dsn = sprintf('mysql:host=%s;port=%s', $host, $port);

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;', str_replace('`', '``', $dbName));
    $pdo->exec($sql);
    fwrite(STDOUT, "Database ensured: {$dbName}\n");
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, 'DB create error: ' . $e->getMessage() . "\n");
    exit(1);
}


