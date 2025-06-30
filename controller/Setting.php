<?php
session_start();
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>kiwi-sisters - 設定</title>

  <link rel="stylesheet" href="../css/setting.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="header">
    <div class="are">オプション</div>
    <a href="/kiwiSisters/controller/StartMenu.php" class="back">戻る→</a>
  </div>

  <div class="menu" id="menu">
    <div class="menu-item active"><span class="indicator">▶</span>
      <button type="button" class="button" data-action="toggle-volume">音量設定</button>
      <div class="volume-status" id="volume-status">音量：ON</div>
    </div>
    <div class="menu-item"><span class="indicator">▶</span>
      <button type="button" class="button" data-href="Logout.php">ログアウト</button>
    </div>
  </div>



  <script>
    const items = document.querySelectorAll(".menu-item");
    let index = 0;
    const audioSelect = new Audio("../music/select.mp3");
    const audioKettei = new Audio("../music/kettei.mp3");
    let isMuted = localStorage.getItem("volumeMuted") === "true";
    const statusDisplay = document.getElementById("volume-status");

    const setVolumeState = () => {
      const vol = isMuted ? 0 : 1;
      audioSelect.volume = vol;
      audioKettei.volume = vol;
      localStorage.setItem("volumeMuted", isMuted); // ← ここが重要
      statusDisplay.textContent = "音量：" + (isMuted ? "OFF" : "ON");
    };

    setVolumeState();

    const updateActive = () => {
      items.forEach((item, i) => {
        item.classList.toggle("active", i === index);
      });
    };

    document.addEventListener("keydown", (e) => {
      if (e.key === "ArrowDown") {
        index = (index + 1) % items.length;
        updateActive();
        audioSelect.currentTime = 0;
        audioSelect.play().catch(() => { });
      } else if (e.key === "ArrowUp") {
        index = (index - 1 + items.length) % items.length;
        updateActive();
        audioSelect.currentTime = 0;
        audioSelect.play().catch(() => { });
      } else if (e.key === "Enter") {
        const activeItem = items[index];
        const button = activeItem.querySelector("button");
        const action = button?.dataset.action;
        const href = button?.dataset.href;

        audioKettei.currentTime = 0;
        audioKettei.play().catch(() => { });

        if (action === "toggle-volume") {
          isMuted = !isMuted;
          setVolumeState();
        } else if (href) {
          setTimeout(() => {
            location.href = href;
          }, 1000);
        }
      }
    });
  </script>
</body>

</html>