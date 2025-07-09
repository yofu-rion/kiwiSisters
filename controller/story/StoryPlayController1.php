<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/story/play1.css">
  <title>Kiwi Sisters</title>
  <style>
    body {
      background: black;
      margin: 0;
      font-family: 'Kiwi Maru', sans-serif;
      background-size: cover;
      background-position: center;
    }
  </style>
</head>

<body>
  <!-- BGM iframe -->
  <iframe id="bgm-frame" src="/kiwiSisters/controller/story/bgm.html" allow="autoplay" style="display:none;"></iframe>

  <div class="full">
    <img id="charImage" class="char-stand" src="" alt="" style="display: none;">
    <div id="choiceArea" class="choices" style="display: none;"></div>
    <div class="kuuhaku">a</div>
    <div class="comment">
      <div class="hako">
        <div class="name" id="charName"></div>
        <div class="text">
          <div id="textArea"></div>
          <button id="nextButton" class="next">></button>
        </div>
        <div class="menu">
          <a href="/kiwiSisters/controller/SaveSelect.php" class="save">セーブ</a>
          <a href="/kiwiSisters/controller/StartMenu.php" class="title">タイトル</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentPage = parseInt(sessionStorage.getItem("currentPage") || "2");

    const charImageMap = <?php echo json_encode([
      '白鷺_通常' => '/kiwiSisters/img/shirasagi_standard.png',
      '白鷺_恐怖' => '/kiwiSisters/img/shirasagi_scared.png',
      '白鷺_笑顔' => '/kiwiSisters/img/shirasagi_smile.png',
      '白鷺_驚き' => '/kiwiSisters/img/shirasagi_surprise.png',
      '白鷺_考察' => '/kiwiSisters/img/shirasagi_thinking.png',
      '白鷺_怒る' => '/kiwiSisters/img/shirasagi_ungry.png',
      '雉真_通常' => '/kiwiSisters/img/kijima_chotosmile.png',
      '雉真_怒る' => '/kiwiSisters/img/kijima_angry.png',
      '雉真_焦り' => '/kiwiSisters/img/kijima_aseri.png',
      '雉真_真顔' => '/kiwiSisters/img/kijima_nomal.png',
      '雉真_笑顔' => '/kiwiSisters/img/kijima_smile.png',
      '雉真_考察' => '/kiwiSisters/img/kijima_thinking.png',
      '鷹森' => '/kiwiSisters/img/takamori_nomal.png',
      '江永' => '/kiwiSisters/img/enaga_standard.png',
      '花子' => '/kiwiSisters/img/hanakosan_smile.png',
      'キーウィ・キウイ' => '/kiwiSisters/img/kiwi.png'
    ]); ?>;

    async function loadPage(page) {
      const bgmFrame = document.getElementById("bgm-frame");
      const bgmWindow = bgmFrame?.contentWindow;
      if (bgmWindow) {
        bgmWindow.postMessage({ type: "saveCurrentTime" }, "*");
      }

      currentPage = page;
      const chapter = parseInt(sessionStorage.getItem("currentChapter") || "1");
      if (!sessionStorage.getItem("currentChapter")) {
        alert("章の選択情報（currentChapter）がありません。章選択画面からやり直してください。");
      }
      const res = await fetch(`/kiwiSisters/controller/getPageData.php?chapter=${chapter}&page=${page}`);
      const data = await res.json();

      document.getElementById("charName").textContent = data.character;
      document.getElementById("textArea").textContent = data.text;

      const bgMap = {
        '廊下': '../../img/rouka.png',
        'トイレ': '../../img/toire.png',
        '学校': '../../img/school.png'
      };
      const bg = bgMap[data.background] || '';
      document.body.style.backgroundImage = `url('${bg}'), linear-gradient(180deg, rgba(98, 9, 20, 0.97) 77.49%, rgba(200, 19, 40, 0.97) 100%)`;

      const charImg = document.getElementById("charImage");
      const rawIllustration = Array.isArray(data.illustration)
        ? data.illustration[0]?.toString().trim()
        : (data.illustration || "").toString().trim();

      let imageSrc = charImageMap[rawIllustration];
      if (!imageSrc && rawIllustration) {
        const base = rawIllustration.split('_')[0].trim();
        imageSrc =
          charImageMap[`${base}_通常`] ||
          Object.entries(charImageMap).find(([key]) =>
            key.startsWith(base)
          )?.[1];
      }

      if (imageSrc) {
        charImg.src = imageSrc;
        charImg.alt = rawIllustration;
        charImg.style.display = "block";
      } else {
        charImg.style.display = "none";
      }

      const choiceArea = document.getElementById("choiceArea");
      choiceArea.innerHTML = "";
      if (data.next_state == 2) {
        [data.choice1, data.choice2, data.jumpTarget].forEach(choice => {
          if (choice && /(.+?)\((\d+)\)/.test(choice)) {
            const [, label, pageNum] = choice.match(/(.+?)\((\d+)\)/);
            const btn = document.createElement("button");
            btn.textContent = label;
            btn.onclick = () => loadPage(pageNum);
            choiceArea.appendChild(btn);
          }
        });
        choiceArea.style.display = "block";
      } else {
        choiceArea.style.display = "none";
      }

      if (bgmWindow) {
        const rawBgm = (data.bgm || "").trim();
        const lastBgm = sessionStorage.getItem("lastBgm") || "";
        const lastTime = parseFloat(sessionStorage.getItem("bgmTime") || "0");

        const effectiveBgm = rawBgm || lastBgm; // 空文字なら前回のBGMを使う
        const isSameBgm = effectiveBgm === lastBgm;
        const currentTime = isSameBgm ? lastTime : 0;
        const firstSend = !sessionStorage.getItem("lastBgmSent");

        // 条件を満たすときだけ postMessage 送信
        if (
          firstSend ||
          !isSameBgm ||
          Math.abs(currentTime - lastTime) > 1
        ) {
          bgmWindow.postMessage({ type: "setBgm", bgm: effectiveBgm, currentTime }, "*");
          sessionStorage.setItem("lastBgm", effectiveBgm);
          sessionStorage.setItem("bgmTime", currentTime.toString());
          sessionStorage.setItem("lastBgmSent", "true");
        }
      }



      document.getElementById("nextButton").onclick = () => loadPage(page + 1);
      sessionStorage.setItem("currentPage", page);
      history.replaceState(null, "", `StoryPlayController1.php`);
    }

    window.addEventListener("DOMContentLoaded", () => {
      let initialPage = parseInt(sessionStorage.getItem("currentPage"), 10);
      if (isNaN(initialPage) || initialPage < 2) {
        initialPage = 2;
        sessionStorage.setItem("currentPage", "2");
      }
      currentPage = initialPage;
      loadPage(initialPage);
    });

    document.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        loadPage(currentPage + 1);
      }
    });
  </script>
</body>

</html>