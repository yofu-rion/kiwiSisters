<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', rtrim($uri, '/'));
$pagePart = end($parts);

// 章番号が指定されていなければ 1 にリダイレクト
if ($pagePart === 'StorySelectController.php') {
    header("Location: /kiwiSisters/controller/StorySelectController.php/1");
    exit;
}

$page = is_numeric($pagePart) ? intval($pagePart) : 1;

// 1〜4以外は第1章にフォールバック
if ($page < 1 || $page > 4) {
    $page = 1;
}

$stories = [
    1 => ["title" => "鷺の話", "image" => "/kiwiSisters/img/story.png"],
    2 => ["title" => "雉の話", "image" => "/kiwiSisters/img/story.png"],
    3 => ["title" => "鷹の話", "image" => "/kiwiSisters/img/story.png"],
    4 => ["title" => "飛べない鳥の話", "image" => "/kiwiSisters/img/story.png"],
];

$current = $stories[$page];
$prevPage = $page > 1 ? $page - 1 : null;
$nextPage = $page < 4 ? $page + 1 : null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kiwi-sisters - 章選択</title>
    <link rel="stylesheet" href="/kiwiSisters/css/storySelect.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
    <?php if ($prevPage): ?>
        <a href="/kiwiSisters/controller/StorySelectController.php/<?= $prevPage ?>" class="arrow left">◀</a>
    <?php endif; ?>
    <div class="kokuban">
        <div class="chapter-content">
            <h2>第<?= $page ?>章</h2>
            <h1><?= htmlspecialchars($current["title"]) ?></h1>
            <div class="image">
                <img src="<?= $current["image"] ?>" alt="第<?= $page ?>章の画像" class="img"/>
            </div>
            <div class="buttons">
                <a href="/kiwiSisters/controller/StoryPlayController.php/<?= $page ?>" class="start">はじめる</a>
                <a href="/kiwiSisters/controller/StartController.php" class="title">タイトルへ</a>
            </div>
        </div>
    </div>

    <?php if ($nextPage): ?>
        <a href="/kiwiSisters/controller/StorySelectController.php/<?= $nextPage ?>" class="arrow right">▶</a>
    <?php endif; ?>
</body>

</html>