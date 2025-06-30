<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 未ログインならログイン画面へリダイレクト
if (!isset($_SESSION['login'])) {
    header('Location: ../index.php');
    exit;
}

$user = htmlspecialchars($_SESSION['login']['name'], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>kiwi-sisters-start</title>
    <link rel="stylesheet" href="../css/start.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>

<body>
    <audio id="kettei-sound" src="../music/kettei.mp3" preload="auto"></audio>
    <audio id="select-sound" src="../music/select.mp3" preload="auto"></audio>
    <div class="choice">
        <div class="title-place">
            <div class="user">ようこそ<?php echo $user; ?>さん</div>
            <h1 class="title">タイトル</h1>
        </div>
        <div class="menu" id="menu">
            <div class="menu-item active"><span class="indicator">▶</span>
                <button type="button" class="button" data-href="StorySelectController.php">話を選ぶ</button>
            </div>
            <div class="menu-item"><span class="indicator">▶</span>
                <button type="button" class="button" data-href="DataLoadController.php">続きから</button>
            </div>
            <div class="menu-item"><span class="indicator">▶</span>
                <button type="button" class="button" data-href="Setting.php">オプション</button>
            </div>
        </div>
    </div>

    <div class="illust">
        <h1 class="h1">イラストが乗る予定</h1>
    </div>

    <div id="fade-overlay"></div>

    <script>
        const items = document.querySelectorAll(".menu-item");
        const audio = document.getElementById("kettei-sound");
        const fadeOverlay = document.getElementById("fade-overlay");
        const audioSelect = document.getElementById("select-sound");

        // ✅ 音量ON/OFFを localStorage から読み取り反映
        const isMuted = localStorage.getItem("volumeMuted") === "true";
        audioSelect.volume = isMuted ? 0 : 1;
        audio.volume = isMuted ? 0 : 1;

        let index = 0;

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
                if (button) {
                    const targetUrl = button.dataset.href;
                    audio.currentTime = 0;
                    audio.play().catch(() => { });
                    fadeOverlay.classList.add("fade-out");
                    setTimeout(() => {
                        location.href = targetUrl;
                    }, 2000);
                }
            }
        });

        window.onpageshow = function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>


    <div class="illust">
        <h1 class="h1">イラストが乗る予定</h1>
    </div>
</body>

</html>