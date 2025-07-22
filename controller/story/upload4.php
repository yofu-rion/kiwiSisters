<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    $correctjumpTarget = $_POST['correctjumpTarget'] ?? 1;
    $incorrectjumpTarget = $_POST['incorrectjumpTarget'] ?? 1;
    $chapter = $_POST['chapter'] ?? 4;

    // 失敗カウント初期化
    if (!isset($_SESSION['failure_count_4'])) {
        $_SESSION['failure_count_4'] = 0;
    }

    $code = file_get_contents($file['tmp_name']);
    $code = preg_replace('/^\s*<\?php\s*/', '', $code);
    $code = preg_replace('/\s*\?>\s*$/', '', $code);

    ob_start();
    $evalSucceeded = true;
    try {
        eval($code);
    } catch (Throwable $e) {
        error_log("Eval error: " . $e->getMessage());
        $evalSucceeded = false;
    }
    $output = ob_get_clean();

    if ($evalSucceeded && strpos($output, "Memory recovered.") !== false) {
        $nextPage = $correctjumpTarget;
        $_SESSION['cleared_program_4'] = true;
        $_SESSION['failure_count_4'] = 0;  // 成功したらリセット
    } else {
        $_SESSION['failure_count_4'] += 1;

        if ($_SESSION['failure_count_4'] >= 5) {
            // 5回失敗したら BadEnd に飛ばす
            $nextPage = 320;
        } else {
            $nextPage = $incorrectjumpTarget;
        }
    }

    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /kiwiSisters/controller/story/StoryPlayController4.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
