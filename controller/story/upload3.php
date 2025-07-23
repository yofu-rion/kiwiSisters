<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ログイン確認
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);
    error_log("uploaded filename: $filename");

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    $correctjumpTarget = $_POST['correctjumpTarget'] ?? 1;
    $incorrectjumpTarget = $_POST['incorrectjumpTarget'] ?? 1;
    $chapter = $_POST['chapter'] ?? 3;

    $status = null;
    $code = file_get_contents($file['tmp_name']);
    $code = preg_replace('/^\s*<\?php\s*/', '', $code);
    $code = preg_replace('/\s*\?>\s*$/', '', $code);

    try {
        eval ($code);
    } catch (Throwable $e) {
        error_log("🔥 Eval error: " . $e->getMessage());
        echo "Eval error: " . $e->getMessage();
        exit;
    }

    error_log("eval result status: $status");

    if ($status === "ok") {
        error_log("✅ 判定: 正解と判断。次のページ = $correctjumpTarget");
        $nextPage = $correctjumpTarget;
        $_SESSION['cleared_program_3'] = true;

        // データベース接続してprogressを更新
        try {
            $pdo = new PDO(
                'mysql:host=127.0.0.1;dbname=kiwi_datas;charset=utf8',
                'staff',
                'password'
            );

            // 現在のprogressを取得
            $selectSql = $pdo->prepare('SELECT progress FROM login WHERE name = ?');
            $selectSql->execute([$username]);
            $currentProgress = $selectSql->fetchColumn();

            // progressが5の倍数でなければ5を掛ける
            if ($currentProgress % 5 !== 0) {
                $newProgress = $currentProgress * 5;
                $updateSql = $pdo->prepare('UPDATE login SET progress = ? WHERE name = ?');
                $updateSql->execute([$newProgress, $username]);
            }

        } catch (PDOException $e) {
            error_log('Progress更新エラー: ' . $e->getMessage());
        }
    } else {
        error_log("❌ 判定: 不正解と判断。次のページ = $incorrectjumpTarget");
        $nextPage = $incorrectjumpTarget;
    }


    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /controller/story/StoryPlayController3.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
