<?php
$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);
    $targetPath = $uploadDir . $filename;

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo "アップロード成功：{$filename}";
    } else {
        echo "アップロードに失敗しました。";
    }
} else {
    echo "ファイルが選択されていません。";
}
