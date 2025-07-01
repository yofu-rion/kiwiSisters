<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ログイン確認
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

function loadSlotData($slotNumber)
{
  $path = __DIR__ . "/../save/slot{$slotNumber}.php";
  if (file_exists($path)) {
    return include $path;
  }
  return null;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>ロードスロット選択</title>
  <link rel="stylesheet" href="../css/save_select.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="container">
    <h1>ロードするスロットを選んでください</h1>
    <ul class="slot-list">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <?php $data = loadSlotData($i); ?>
        <li>
          <?php if ($data): ?>
            <?php
              $timestamp = isset($data['timestamp']) ? htmlspecialchars($data['timestamp']) : '未保存';
              $pageNumber = isset($data['page']) ? htmlspecialchars((string) $data['page']) : '?';
            ?>
            <div class="slot-info">スロット<?= $i ?>：<?= $timestamp ?> に Page <?= $pageNumber ?> を保存済み</div>
            <a class="save-button" href="/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= $pageNumber ?>">ロード</a>
          <?php else: ?>
            <div class="slot-info">スロット<?= $i ?>：空</div>
            <span class="save-button disabled">ロード不可</span>
          <?php endif; ?>
        </li>
      <?php endfor; ?>
    </ul>
    <a class="back-link" href="StartMenu.php">戻る</a>
  </div>
</body>

</html>
