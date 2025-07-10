<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ログイン確認
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

// データベース接続
$pdo = new PDO(
  'mysql:host=localhost;dbname=kiwi_datas;charset=utf8',
  'staff',
  'password'
);

// セーブスロット読み込み（データベースから）
$slots = [];

try {
  // 現在のユーザーのセーブデータを全て取得
  $sql = $pdo->prepare('SELECT slot_num, page, chapter, bgm, timestamp FROM save_data WHERE user_name = ? ORDER BY slot_num');
  $sql->execute([$username]);

  // 結果を連想配列に変換
  $saveData = [];
  while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    $saveData[$row['slot_num']] = [
      'page' => $row['page'],
      'chapter' => $row['chapter'],
      'bgm' => $row['bgm'],
      'timestamp' => $row['timestamp']
    ];
  }

  // スロット1-4の情報を整理
  for ($i = 1; $i <= 4; $i++) {
    $slots[$i] = isset($saveData[$i]) ? $saveData[$i] : null;
  }
} catch (PDOException $e) {
  error_log('ロードエラー: ' . $e->getMessage());
  // エラー時は空のスロットを設定
  for ($i = 1; $i <= 4; $i++) {
    $slots[$i] = null;
  }
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
        <?php $data = $slots[$i]; ?>
        <li>
          <?php if ($data): ?>
            <?php
            $timestamp = isset($data['timestamp']) ? htmlspecialchars($data['timestamp']) : '未保存';
            $pageNumber = isset($data['page']) ? htmlspecialchars((string) $data['page']) : '?';
            $chapterNumber = isset($data['chapter']) ? htmlspecialchars((string) $data['chapter']) : '?';
            ?>
            <div class="slot-info">スロット<?= $i ?>：<?= $timestamp ?> に Chapter <?= $chapterNumber ?> Page <?= $pageNumber ?>
              を保存済み</div>
            <a class="save-button"
              href="/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= $pageNumber ?>&chapter=<?= $chapterNumber ?>&bgm=<?= urlencode($data['bgm']) ?>">
              ロード</a>
          <?php else: ?>
            <div class="slot-info">スロット<?= $i ?>：空</div>
            <span class="save-button disabled">ロード不可</span>
          <?php endif; ?>
        </li>
      <?php endfor; ?>
    </ul>
    <a class="back" href="StartMenu.php">←戻る</a>
  </div>
</body>

</html>