<?php
// chapter3_clear フラグ確認
session_start();
if (!isset($_SESSION['flags']['chapter3_clear'])) {
    header("Location: /kiwiSisters/controller/StorySelectController.php");
    exit;
}

// このページは4章の専用表示
$title = "飛べない鳥の話";
$image = "/kiwiSisters/img/story_final.png";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>第4章 - 飛べない鳥の話</title>
    <link rel="stylesheet" href="/kiwiSisters/css/storySelectFinal.css">
</head>
<body>
    <audio id="select-sound" src="/kiwiSisters/music/select.mp3" preload="auto"></audio>

    <div class="final-kokuban">
        <h2>第4章</h2>
        <h1><?= htmlspecialchars($title) ?></h1>
        <div class="image">
            <img src="<?= $image ?>" alt="第4章の画像" class="img" />
        </div>
        <div class="buttons">
            <button class="start" id="start-button">はじめる</button>
            <a href="/kiwiSisters/controller/StorySelectController.php" class="title">章選択へ戻る</a>
        </div>
    </div>

    <script>
        document.getElementById("start-button").addEventListener("click", () => {
            location.href = "/kiwiSisters/controller/StoryPlayController.php/4";
        });
    </script>
</body>
</html>
