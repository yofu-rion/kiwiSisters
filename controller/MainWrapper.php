<!-- /kiwiSisters/controller/MainWrapper.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Kiwi Sisters</title>
  <style>
    html, body {
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
<body>
  <!-- BGM 専用 iframe -->
  <iframe id="bgm-frame" src="/kiwiSisters/controller/story/bgm.html" allow="autoplay"></iframe>

  <!-- ストーリー表示 iframe（初期ページを指定）-->
  <iframe id="story-frame" src="/kiwiSisters/controller/story/StoryPlayController1.php?page=1"></iframe>
</body>
</html>
