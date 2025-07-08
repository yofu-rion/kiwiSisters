<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>Kiwi Sisters</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      overflow: hidden;
    }
    #game-frame {
      width: 100vw;
      height: 100vh;
      border: none;
    }
  </style>
</head>
<body>
  <!-- BGMを管理する常駐 iframe -->
  <iframe id="bgm-frame" src="/kiwiSisters/controller/story/bgm.html" style="display: none;" allow="autoplay"></iframe>

  <!-- ストーリーページを切り替えて表示 -->
  <?php
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $chapter = isset($_GET['chapter']) ? (int)$_GET['chapter'] : 1;
    $src = "/kiwiSisters/controller/story/StoryPlayController1.php?page={$page}&chapter={$chapter}";


  ?>
  <iframe id="game-frame" src="<?= htmlspecialchars($src) ?>"></iframe>
</body>
</html>
