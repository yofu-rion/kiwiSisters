<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['name'], $_POST['password'])
) {
    header('Location: ../index.php');
    exit;
}

$pdo = new PDO(
    'mysql:host=127.0.0.1;dbname=kiwi_datas;charset=utf8',
    'staff',
    'password'
);

$sql = $pdo->prepare('SELECT * FROM login WHERE name=? AND password=?');
$sql->execute([$_POST['name'], $_POST['password']]);

if ($row = $sql->fetch()) {
    $_SESSION['login'] = ['name' => $row['name']];
    header('Location: StartMenu.php');
    exit;
} else {
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="ja">
    <head>
    <meta charset="UTF-8">
    <title>ログインエラー</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="../css/error.css">
    </head>
    <body>
        <h1 class="error-title">IDまたはパスワードが違います</h1>
        <p class="error-message">3秒後にログインページに戻ります...</p>
        <script>
            setTimeout(() => location.href = '../index.php', 3000);
        </script>
    </body>
    </html>
    HTML;
    exit;
}