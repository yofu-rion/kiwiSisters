<?php
$filepath = __DIR__ . '/../problems/problem1.php'; // 問題ファイル

if (file_exists($filepath)) {
    header('Content-Type: application/x-httpd-php');
    header('Content-Disposition: attachment; filename="problem1.php"');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    echo "ファイルが見つかりません。";
}
