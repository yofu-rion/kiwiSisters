<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// .env 読み込み
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// DB接続
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );

    echo "<p>✅ DB接続成功</p>";

    // login テーブル作成
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS login (
            name VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
            password VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
            progress INT NOT NULL DEFAULT 1,
            PRIMARY KEY (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ");
    echo "<p>✅ login テーブル作成成功</p>";

    // save_data テーブル作成
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS save_data (
            user_name VARCHAR(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
            slot_num INT NOT NULL,
            page INT NOT NULL,
            timestamp DATETIME NOT NULL,
            chapter INT NOT NULL,
            bgm VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
            background VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ");
    echo "<p>✅ save_data テーブル作成成功</p>";

} catch (PDOException $e) {
    echo "<p>❌ エラー: " . $e->getMessage() . "</p>";
}
