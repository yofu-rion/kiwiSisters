<?php
$uri = $_SERVER['REQUEST_URI'];
$parts = explode('/', rtrim($uri, '/'));
$pagePart = end($parts);

if ($pagePart === 'StorySelectController.php') {
    header("Location: /kiwiSisters/controller/StorySelectController.php/1");
    exit;
}

$page = is_numeric($pagePart) ? intval($pagePart) : 1;

if ($page < 1 || $page > 4) {
    $page = 1;
}

$isFinalChapter = $page === 4;
// ここで4章をはじめられるか管理してるよう
$unlockFinalChapter = false;

$stories = [
    1 => ["title" => "鷺の話", "image" => "/kiwiSisters/img/story.png"],
    2 => ["title" => "雉の話", "image" => "/kiwiSisters/img/story.png"],
    3 => ["title" => "鷹の話", "image" => "/kiwiSisters/img/story.png"],
    4 => ["title" => "???????", "image" => "/kiwiSisters/img/story.png"],
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

<body class="<?= $isFinalChapter ? 'final-page' : '' ?>">
    <audio id="select-sound" src="/kiwiSisters/music/select.mp3" preload="auto"></audio>
    <?php if ($prevPage): ?>
        <a href="/kiwiSisters/controller/StorySelectController.php/<?= $prevPage ?>" class="arrow left">◀</a>
    <?php endif; ?>
    <div class="kokuban<?= $isFinalChapter ? ' final-chapter' : '' ?>">
        <div class="chapter-content">
            <h2>
                <?php if ($isFinalChapter): ?>
                    蝗帷ｫ?
                <?php else: ?>
                    第<?= $page ?>章
                <?php endif; ?>
            </h2>

            <h1><?= htmlspecialchars($current["title"]) ?></h1>
            <div class="image">
                <img src="<?= $current["image"] ?>" alt="第<?= $page ?>章の画像" class="img" />
            </div>
            <div class="buttons">
                <?php if (!$isFinalChapter || ($isFinalChapter && $unlockFinalChapter)): ?>
                    <button class="start" id="start-button">はじめる</button>
                <?php endif; ?>
                <a href="/kiwiSisters/controller/StartMenu.php" class="title">タイトルへ</a>
            </div>
        </div>
    </div>

    <?php if ($nextPage): ?>
        <a href="/kiwiSisters/controller/StorySelectController.php/<?= $nextPage ?>" class="arrow right">▶</a>
    <?php endif; ?>

    <div id="modal-overlay" class="modal-overlay hidden">
        <div class="modal-content">
            <p>この章を始めますか？</p>
            <div class="modal-buttons">
                <button id="modal-ok">はい</button>
                <button id="modal-cancel">いいえ</button>
            </div>
        </div>
    </div>

    <script>
        const audioSelect = document.getElementById("select-sound");
        const chapterPage = <?= $page ?>;
        const storyUrl = "/kiwiSisters/controller/story/StoryPlayController" + chapterPage + ".php?page=1";
        const modal = document.getElementById("modal-overlay");
        const okButton = document.getElementById("modal-ok");
        const cancelButton = document.getElementById("modal-cancel");

        const showModal = () => {
            modal.classList.remove("hidden");
        };

        const hideModal = () => {
            modal.classList.add("hidden");
        };

        const startButton = document.getElementById("start-button");

        if (startButton) {
            startButton.addEventListener("click", () => {
                showModal();
            });
        }
        document.addEventListener("keydown", (e) => {
            if (!modal.classList.contains("hidden")) return;

            if (e.key === "ArrowLeft") {
                <?php if ($prevPage): ?>
                    window.location.href = "/kiwiSisters/controller/StorySelectController.php/<?= $prevPage ?>";
                    audioSelect.currentTime = 0;
                    audioSelect.play().catch(() => { });
                <?php endif; ?>
            } else if (e.key === "ArrowRight") {
                <?php if ($nextPage): ?>
                    window.location.href = "/kiwiSisters/controller/StorySelectController.php/<?= $nextPage ?>";
                    audioSelect.currentTime = 0;
                    audioSelect.play().catch(() => { });
                <?php endif; ?>
            } else if (e.key === "Enter") {
                showModal();
            }
        });
        okButton.addEventListener("click", () => {
            window.location.href = storyUrl;
        });
        cancelButton.addEventListener("click", () => {
            hideModal();
        });
    </script>
</body>

</html>