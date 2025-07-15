<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    $correctjumpTarget = $_POST['correctjumpTarget'] ?? 1;
    $incorrectjumpTarget = $_POST['incorrectjumpTarget'] ?? 1;
    $chapter = $_POST['chapter'] ?? 3;

    ob_start(); // eval の標準出力キャプチャ
    $code = file_get_contents($file['tmp_name']);
    $code = '?>' . $code . '<?php ';
    eval($code);
    $output = ob_get_clean();

    // 判定: UTF-8に正常変換された「もっと下においで～キャラクター」を含んでいるか
    $expected = "もっと下においで～キャラクター";

    if (strpos($output, $expected) !== false) {
        $nextPage = $correctjumpTarget;
    } else {
        $nextPage = $incorrectjumpTarget;
    }

    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /kiwiSisters/controller/story/StoryPlayController1.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
