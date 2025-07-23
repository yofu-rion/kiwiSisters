<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>kiwi-sisters-signup-done</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ?>

    <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // .env を読み込む
    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // DB接続
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS']
    );

    // login テーブル（進捗カラム付き）
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS login (
            name VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
            password VARCHAR(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
            progress INT NOT NULL DEFAULT 1,
            PRIMARY KEY (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ");

    // save_data テーブル
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


    $sql = $pdo->prepare('SELECT * FROM login WHERE name = ?');
    $sql->execute([$_REQUEST['name']]);



    if (empty($sql->fetchAll())) {

        if ($_REQUEST['password'] == $_REQUEST['password_check']) {
            $hashed = password_hash($_REQUEST['password'], PASSWORD_DEFAULT);
            $sql = $pdo->prepare('INSERT INTO login (name, password) VALUES (?, ?)');
            $sql->execute([$_REQUEST['name'], $hashed]);


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
            // echo 'パスワードが一致しません。';
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
        // フロントおねしゃす
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
</body>