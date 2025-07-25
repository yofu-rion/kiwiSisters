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

    if ($evalSucceeded && strpos($output, "忘れていた記憶") !== false) {
        $nextPage = $correctjumpTarget;
        $_SESSION['cleared_program_4'] = true;
        $_SESSION['failure_count_4'] = 0;
        error_log("✅ 成功: メモリ回復検出。次ページ = $correctjumpTarget");
    } else {
        $_SESSION['failure_count_4'] += 1;

        if ($_SESSION['failure_count_4'] >= 5) {
            $nextPage = 320; // BadEnd
            error_log("💀 5回失敗: BadEnd に遷移");
        } else {
            $nextPage = $incorrectjumpTarget;
            error_log("❌ 失敗: 回数 = {$_SESSION['failure_count_4']} → 次ページ = $incorrectjumpTarget");
        }
    }

    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /kiwiSisters/controller/story/StoryPlayController4.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
