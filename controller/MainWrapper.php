<?php
$page = $_GET['page'] ?? 1;
$chapter = $_GET['chapter'] ?? 1;
$storySrc = "/kiwiSisters/controller/story/StoryPlayController1.php?page={$page}&chapter={$chapter}";
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
  <iframe id="story-frame" src="<?= htmlspecialchars($storySrc) ?>"></iframe>

  <script>
  const params = new URLSearchParams(window.location.search);
  const initialPage = params.get("page") || "1";
  const chapter = params.get("chapter") || "1";

  history.replaceState(null, "", `/kiwiSisters/controller/story/StoryPlayController1.php?page=${initialPage}&chapter=${chapter}`);

  function goToPage(page) {
    const storyFrame = document.getElementById("story-frame");
    const url = `/kiwiSisters/controller/story/StoryPlayController1.php?page=${page}&chapter=${chapter}`;
    storyFrame.contentWindow.postMessage({ type: "changePage", url }, "*");
    history.replaceState(null, "", url);
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      console.log("🚪 MainWrapper で Enter 押下を検出");
      const storyFrame = document.getElementById("story-frame");
      storyFrame.contentWindow.postMessage({ type: "enterPressed" }, "*");
    }
  });
</script>

</body>

</html>