<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ログイン確認
if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

// セーブスロット読み込み
$slots = [];

for ($i = 1; $i <= 4; $i++) {
    $slotFile = __DIR__ . "/../save/slot{$i}.php";
    if (file_exists($slotFile)) {
        $slots[$i] = include($slotFile);
    } else {
        $slots[$i] = null;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ロード画面</title>
  <link rel="stylesheet" href="/kiwiSisters/css/load.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="header">
    <div class="are">ロード</div>
    <a href="/kiwiSisters/controller/StartMenu.php" class="back">戻る→</a>
  </div>

  <div class="save">
    <?php foreach ($slots as $i => $data): ?>
      <div class="save-block">
        <p>スロット<?= $i ?></p>
        <?php if (is_array($data) && isset($data['timestamp'], $data['page'])): ?>
          <p>セーブ日時：<?= htmlspecialchars($data['timestamp']) ?></p>
          <a href="/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= htmlspecialchars((string)$data['page']) ?>" class="load-link">ロード</a>
        <?php else: ?>
          <p>セーブデータがありません</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
