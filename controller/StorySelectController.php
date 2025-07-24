<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// ログイン確認
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

// クエリパラメータからページ番号を取得
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1 || $page > 4) {
  $page = 1;
}

$isFinalChapter = $page === 4;

// DB接続してprogressチェック
try {
  $pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
  );

  $sql = $pdo->prepare('SELECT progress FROM login WHERE name = ?');
  $sql->execute([$username]);
  $progress = $sql->fetchColumn();

  $unlockFinalChapter = ($progress % 2 === 0) && ($progress % 3 === 0) && ($progress % 5 === 0);
} catch (PDOException $e) {
  error_log('Progress取得エラー: ' . $e->getMessage());
  $unlockFinalChapter = false;
}

$stories = [
  1 => ["title" => "鷺の話", "image" => "/img/story1.png"],
  2 => ["title" => "雉の話", "image" => "/img/story2.png"],
  3 => ["title" => "鷹の話", "image" => "/img/story3.png"],
  4 => [
    "title" => $unlockFinalChapter ? "飛べない鳥の話" : "?????",
    "image" => $unlockFinalChapter ? "/img/story4.png" : "/img/story.png"
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
  <link rel="stylesheet" href="/css/storySelect.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
</head>

<body>
  <script>
    // fallback: currentChapter が存在しなければセット
    if (!sessionStorage.getItem('currentChapter')) {
      sessionStorage.setItem('currentChapter', <?= $page ?>);
    }
  </script>

  <audio id="select-sound" src="/music/select.mp3" preload="auto"></audio>
  <audio id="kettei-sound" src="/music/kettei.mp3" preload="auto"></audio>

  <?php if ($prevPage): ?>
    <a href="/controller/StorySelectController.php?page=<?= $prevPage ?>" class="arrow left">◀</a>
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
        <a href="/controller/StartMenu.php" class="title">タイトルへ</a>
      </div>
    </div>
  </div>
  <?php if ($nextPage): ?>
    <a href="/controller/StorySelectController.php?page=<?= $nextPage ?>" class="arrow right">▶</a>
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
      const stories = <?= json_encode($stories, JSON_UNESCAPED_UNICODE) ?>;
      let currentPage = <?= $page ?>;
      const maxPage = 4;
      const unlockFinalChapter = <?= $unlockFinalChapter ? 'true' : 'false' ?>;
      const isFinalChapter = (page) => page === 4 && unlockFinalChapter;

      document.addEventListener("DOMContentLoaded", () => {
        const showModal = () => {
          document.getElementById("modal-overlay").classList.remove("hidden");
        };

        const hideModal = () => {
          document.getElementById("modal-overlay").classList.add("hidden");
        };

        const setupModalHandlers = () => {
          const ok = document.getElementById("modal-ok");
          const cancel = document.getElementById("modal-cancel");

          cancel.onclick = hideModal;
          ok.onclick = () => {
            sessionStorage.setItem("currentChapter", currentPage);
            sessionStorage.setItem("currentPage", 1);

            const fadeOverlay = document.getElementById("fade-overlay");
            const chapterTitle = document.getElementById("chapter-title");
            const kettei = document.getElementById("kettei-sound");

            chapterTitle.textContent = `第${currentPage}章　　${stories[currentPage].title}`;
            fadeOverlay.classList.add("fade-in");
            chapterTitle.style.opacity = "1";

            kettei.currentTime = 0;
            kettei.play().catch(() => { });

            setTimeout(() => chapterTitle.style.opacity = "0", 2500);
            setTimeout(() => {
              window.location.href = `/controller/story/StoryPlayController${currentPage}.php`;
            }, 3500);
          };
        };

        const updateUI = () => {
          const story = stories[currentPage];
          const title = isFinalChapter(currentPage) ? '最終章' : `第${currentPage}章`;
          const displayTitle = story.title;
          const imagePath = story.image;

          document.querySelector(".chapter-content h2").textContent = title;
          document.querySelector(".chapter-content h1").textContent = displayTitle;
          document.querySelector(".img").src = imagePath;
          document.querySelector("body").className = isFinalChapter(currentPage) ? 'final-page' : '';
          document.querySelector(".kokuban").className = `kokuban${isFinalChapter(currentPage) ? ' final-chapter' : ''}`;
          document.getElementById("start-button")?.remove();

          if (isFinalChapter(currentPage) || currentPage < 4) {
            const startBtn = document.createElement("button");
            startBtn.className = "start";
            startBtn.id = "start-button";
            startBtn.textContent = "はじめる";
            startBtn.addEventListener("click", showModal);
            document.querySelector(".buttons").prepend(startBtn);
          }

          setupModalHandlers();
        };

        document.addEventListener("keydown", (e) => {
          const select = document.getElementById("select-sound");
          const modal = document.getElementById("modal-overlay");
          if (!modal.classList.contains("hidden")) {
            if (e.key === "Enter") {
              document.getElementById("modal-ok").click();
            }
            return;
          }

          if (e.key === "ArrowRight" && currentPage < maxPage) {
            currentPage++;
            select.currentTime = 0;
            select.play().catch(() => { });
            updateUI();
          } else if (e.key === "ArrowLeft" && currentPage > 1) {
            currentPage--;
            select.currentTime = 0;
            select.play().catch(() => { });
            updateUI();
          } else if (e.key === "Enter") {
            document.getElementById("start-button")?.click();
          }
        });

        updateUI();
      });
    </script>

</body>

</html>