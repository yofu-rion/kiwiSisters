<?php
session_start();

// ログイン確認
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

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

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=kiwi_datas;charset=utf8',
        'staff',
        'password'
    );

    // 現在のprogressを取得
    $sql = $pdo->prepare('SELECT progress FROM login WHERE name = ?');
    $sql->execute([$username]);
    $progress = $sql->fetchColumn();

    // progressが2、3、5全ての倍数かチェック
    $unlockFinalChapter = ($progress % 2 === 0) && ($progress % 3 === 0) && ($progress % 5 === 0);

} catch (PDOException $e) {
    error_log('Progress取得エラー: ' . $e->getMessage());
    $unlockFinalChapter = false;
}

$stories = [
  1 => ["title" => "鷺の話", "image" => "/kiwiSisters/img/story1.png"],
  2 => ["title" => "雉の話", "image" => "/kiwiSisters/img/story2.png"],
  3 => ["title" => "鷹の話", "image" => "/kiwiSisters/img/story3.png"],
  4 => [
    "title" => $unlockFinalChapter ? "飛べない鳥の話" : "?????",
    "image" => $unlockFinalChapter ? "/kiwiSisters/img/story4.png" : "/kiwiSisters/img/story.png"
  ],
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
  <script>
    // fallback: currentChapter が存在しなければセット
    if (!sessionStorage.getItem('currentChapter')) {
      sessionStorage.setItem('currentChapter', <?= $page ?>);
    }
  </script>

  <audio id="select-sound" src="/kiwiSisters/music/select.mp3" preload="auto"></audio>
  <audio id="kettei-sound" src="/kiwiSisters/music/kettei.mp3" preload="auto"></audio>

  <?php if ($prevPage): ?>
    <a href="/kiwiSisters/controller/StorySelectController.php/<?= $prevPage ?>" class="arrow left">◀</a>
  <?php endif; ?>
  <div class="kokuban<?= $isFinalChapter ? ' final-chapter' : '' ?>">
    <div class="chapter-content">
      <h2><?= $isFinalChapter ? '最終章' : "第{$page}章" ?></h2>
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
      <small>(Enterで始まります)</small>
      <div class="modal-buttons">
        <button id="modal-ok">はい</button>
        <button id="modal-cancel">いいえ</button>
      </div>
    </div>
  </div>
  <div>
    <div id="chapter-title"></div>
    <div id="fade-overlay" class="fade-overlay"></div>
    <script>
      const audioSelect = document.getElementById("select-sound");
      const chapterPage = <?= $page ?>;
      const storyUrl = "/kiwiSisters/controller/story/StoryPlayController" + chapterPage + ".php?page=2";
      const modal = document.getElementById("modal-overlay");
      const okButton = document.getElementById("modal-ok");
      const cancelButton = document.getElementById("modal-cancel");
      const fadeOverlay = document.getElementById("fade-overlay");
      const audioKettei = document.getElementById("kettei-sound");

      const showModal = () => {
        modal.classList.remove("hidden");
      };

      const hideModal = () => {
        modal.classList.add("hidden");
      };

      document.getElementById("start-button")?.addEventListener("click", showModal);
      document.getElementById("modal-cancel")?.addEventListener("click", hideModal);
      document.getElementById("modal-ok")?.addEventListener("click", () => {
        sessionStorage.setItem("currentChapter", chapterPage);
        sessionStorage.setItem("currentPage", 1);

        audioKettei.currentTime = 0;
        audioKettei.play().catch(() => { });

        const chapterTitle = document.getElementById("chapter-title");
        chapterTitle.textContent = `第${chapterPage}章　　${"<?= htmlspecialchars($current["title"]) ?>"}`;

        fadeOverlay.classList.add("fade-in");

        setTimeout(() => {
          chapterTitle.style.opacity = "1";
        }, 500);

        setTimeout(() => {
          chapterTitle.style.opacity = "0";
        }, 2500);

        setTimeout(() => {
          window.location.href = `/kiwiSisters/controller/story/StoryPlayController${chapterPage}.php`;
        }, 3500);
      });



      document.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          if (!modal.classList.contains("hidden")) {
            okButton.click();
            return;
          }

          const startButton = document.getElementById("start-button");
          if (startButton) {
            startButton.click();
          }
        }

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
        }
      });

    </script>
</body>

</html>