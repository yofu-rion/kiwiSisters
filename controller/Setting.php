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

<div class="header">
  <div class="are">オプション</div>
  <a href="/kiwiSisters/controller/StartMenu.php" class="back">戻る→</a>
</div>
<div class="settei">
  <div>音量設定</div>
  <a href="Logout.php" class="logout-btn">ログアウト</a>
</div>
</body>

</html>