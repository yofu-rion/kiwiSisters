<?php
session_start();                     // ← すでにログインしている前提
if (!isset($_SESSION['login'])) {    // 未ログインならログイン画面へ
    header('Location: ../index.php');
    exit;
}
?>
<!-- 設定画面 -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>kiwi-sisters - 設定</title>

  <link rel="stylesheet" href="../css/load.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body class="setting-page">
  <h1 class="setting-title">オプション</h1>

  <!-- ここに他の設定項目 … -->

  <!-- ★ ログアウトボタン／リンク -->
  <div class="logout-area">
    <a href="Logout.php" class="logout-btn">ログアウト</a>
  </div>
</body>
</html>
