<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

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
        <?php if ($data): ?>
          <p>セーブ日時：<?= htmlspecialchars($data['timestamp']) ?></p>
          <a href="/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= $data['page'] ?>">ロード</a>
        <?php else: ?>
          <p>セーブデータがありません</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
