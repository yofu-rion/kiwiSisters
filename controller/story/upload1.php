<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    $correctjumpTarget = $_POST['correctjumpTarget'] ?? 1;
    $incorrectjumpTarget = $_POST['incorrectjumpTarget'] ?? 1;

    // アップロードされたファイルの内容を読み込む
    $code = file_get_contents($file['tmp_name']);
    
    $code = '?>' . $code . '<?php ';

    eval($code);  

    if ($doorState === "open") {
        $nextPage = $correctjumpTarget;
    } else {
        $nextPage = $incorrectjumpTarget;
    }

    header("Location: StoryPlayController1.php?page={$nextPage}");

} else {
    echo "ファイルが選択されていません。";
}
