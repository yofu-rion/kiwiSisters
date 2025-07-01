<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

$slot = isset($_GET['slot']) ? intval($_GET['slot']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($slot < 1 || $slot > 4) {
    echo "スロット番号が不正です。";
    exit;
}

$data = [
    'page' => $page,
    'timestamp' => date('Y-m-d H:i:s'),
];

$content = "<?php\nreturn " . var_export($data, true) . ";\n";

// 保存先のパス
$saveDir = dirname(__DIR__) . "/save";
$savePath = $saveDir . "/slot{$slot}.php";

// ディレクトリがなければ作成
if (!is_dir($saveDir)) {
    mkdir($saveDir, 0777, true);
}

// ファイル保存
file_put_contents($savePath, $content);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="3;url=/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= $page ?>">
  <title>セーブ完了</title>
  <link rel="stylesheet" href="../css/save_select.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="container">
    <p class="success-message">スロット<?= $slot ?>にセーブしました！3秒後に戻ります…</p>
  </div>
</body>
</html>
