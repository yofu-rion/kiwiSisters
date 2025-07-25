<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO(
    'mysql:host=localhost;dbname=kiwi_datas;charset=utf8',
    'staff',
    'password'
);

// ユーザー名の重複チェック
$sql = $pdo->prepare('SELECT * FROM login WHERE name = ?');
$sql->execute([$_REQUEST['name']]);
$userExists = $sql->fetch();

if (!$userExists) {
    if ($_REQUEST['password'] === $_REQUEST['password_check']) {
        // ユーザー登録（progress は省略 → デフォルトの1が入る）
        $sql = $pdo->prepare('INSERT INTO login (name, password) VALUES (?, ?)');
        $sql->execute([$_REQUEST['name'], $_REQUEST['password']]);

        // 成功画面
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <title>登録完了</title>
            <link rel="stylesheet" href="../css/error.css">
        </head>
        <body>
            <h1 class="error-title">登録しました</h1>
            <p class="error-message">3秒後にログインページに戻ります...</p>
            <script>
                setTimeout(() => location.href = '../index.php', 3000);
            </script>
        </body>
        </html>
        HTML;
        exit;
    } else {
        // パスワード不一致エラー
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <title>パスワード不一致</title>
            <link rel="stylesheet" href="../css/error.css">
        </head>
        <body>
            <h1 class="error-title">パスワードが一致しません…</h1>
            <p class="error-message">3秒後に新規作成ページに戻ります...</p>
            <script>
                setTimeout(() => location.href = '../controller/SignUpController.php', 3000);
            </script>
        </body>
        </html>
        HTML;
    }
} else {
    // ユーザー名が既に存在している場合
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ID重複</title>
        <link rel="stylesheet" href="../css/error.css">
    </head>
    <body>
        <h1 class="error-title">IDがすでに使用されていますので、変更してください。</h1>
        <p class="error-message">3秒後に新規作成ページに戻ります...</p>
        <script>
            setTimeout(() => location.href = '../controller/SignUpController.php', 3000);
        </script>
    </body>
    </html>
    HTML;
}
?>
