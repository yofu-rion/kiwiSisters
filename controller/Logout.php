<?php
session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

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
        <h1 class="error-title">ログアウトしました</h1>
        <p class="error-message">3秒後にログインページに戻ります...</p>
        <script>
            setTimeout(() => location.href = '../index.php?msg=logout', 3000);
        </script>
    </body>
    </html>
    HTML;
    exit;

