<?php
$target = $_GET['target'] ?? null;

// `target` が指定されていなければデフォルトページへ
if (!$target) {
  $page = $_GET['page'] ?? 1;
  $chapter = $_GET['chapter'] ?? 1;
  $target = "/kiwiSisters/controller/story/StoryPlayController1.php?page={$page}&chapter={$chapter}";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Kiwi Sisters</title>
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
      height: 100%;
    }

    #bgm-frame {
      display: none;
    }

    #story-frame {
      width: 100vw;
      height: 100vh;
      border: none;
    }
  </style>
</head>

<body tabindex="0">
  <!-- ⬆️ BGM iframe -->
  <iframe id="bgm-frame" src="/kiwiSisters/controller/story/bgm.html" allow="autoplay"></iframe>

  <!-- ⬆️ Story iframe -->
  <iframe id="story-frame" src="<?= htmlspecialchars($target) ?>"></iframe>

  <script>
  const params = new URLSearchParams(window.location.search);
  const urlPage = params.get("page");
  const storedPage = sessionStorage.getItem("currentPage");
  const page = urlPage || storedPage || "1";
  const chapter = params.get("chapter") || "1";

  // ✅ iframe の src に反映
  const storyFrame = document.getElementById("story-frame");
  const url = `/kiwiSisters/controller/story/StoryPlayController1.php?page=${page}&chapter=${chapter}`;
  if (storyFrame && storyFrame.src !== url) {
    storyFrame.src = url;
  }

  // ✅ 表示URLを iframe 内の StoryPlayController に合わせて整合性をとる
  history.replaceState(null, "", url);

  // ✅ ページ番号をセッションストレージに保存
  sessionStorage.setItem("currentPage", page);

  function goToPage(page) {
    sessionStorage.setItem("currentPage", page); // ✅ ページ遷移時にも記録
    const url = `/kiwiSisters/controller/story/StoryPlayController1.php?page=${page}&chapter=${chapter}`;
    const storyFrame = document.getElementById("story-frame");
    if (storyFrame?.contentWindow) {
      storyFrame.contentWindow.postMessage({ type: "changePage", url }, "*");
    }
    history.replaceState(null, "", url);
  }

  // ✅ Enterキー検出で Story 側にメッセージ送信
  document.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      console.log("🚪 MainWrapper で Enter 押下を検出");
      const storyFrame = document.getElementById("story-frame");
      if (storyFrame?.contentWindow) {
        storyFrame.contentWindow.postMessage({ type: "enterPressed" }, "*");
      }
    }
  });
</script>



</body>

</html>