<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    } else {
        error_log("❌ 判定: 不正解と判断。次のページ = $incorrectjumpTarget");
        $nextPage = $incorrectjumpTarget;
    }


    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /kiwiSisters/controller/story/StoryPlayController3.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
