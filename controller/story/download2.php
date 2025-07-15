<?php
$filepath = __DIR__ . '/../../problems/problem2.php';

if (file_exists($filepath)) {
    header('Content-Type: application/x-httpd-php');
    header('Content-Disposition: attachment; filename="problem3.php"');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    echo "ファイルが見つかりません。";
}
