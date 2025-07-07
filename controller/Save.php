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

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

if ($slot < 1 || $slot > 4) {
    echo "スロット番号が不正です。";
    exit;
}

$pdo = new PDO(
    'mysql:host=localhost;dbname=kiwi_datas;charset=utf8',
    'staff',
    'password'
);

try {
    // まず既存データを削除してから挿入（確実に上書き）
    $deleteSql = $pdo->prepare('DELETE FROM save_data WHERE user_name = ? AND slot_num = ?');
    $deleteSql->execute([$username, $slot]);
    
    // 新しいセーブデータを挿入
    $insertSql = $pdo->prepare('
        INSERT INTO save_data (user_name, slot_num, page, timestamp)
        VALUES (?, ?, ?, NOW())
    ');
    $insertSql->execute([$username, $slot, $page]);
    
    $saveSuccess = true;
} catch (PDOException $e) {
    $saveSuccess = false;
    error_log('セーブエラー: ' . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="1;url=/kiwiSisters/controller/story/StoryPlayController1.php?page=<?= $page ?>">
  <title>セーブ完了</title>
  <link rel="stylesheet" href="../css/save_select.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="container">
    <?php if (isset($saveSuccess) && $saveSuccess): ?>
      <p class="success-message">スロット<?= $slot ?>にセーブしました！3秒後に戻ります…</p>
    <?php else: ?>
      <p class="error-message">セーブに失敗しました。再試行してください。</p>
    <?php endif; ?>
  </div>
</body>
</html>
