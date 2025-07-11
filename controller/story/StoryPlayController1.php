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
          <a href="#" class="save" id="saveButton">セーブ</a>
          <a href="/kiwiSisters/controller/StartMenu.php" class="title">タイトル</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentPage = parseInt(sessionStorage.getItem("currentPage") || "2");
    const charImageMap = {
      '白鷺_通常': '/kiwiSisters/img/shirasagi_standard.png',
      '白鷺_恐怖': '/kiwiSisters/img/shirasagi_scared.png',
      '白鷺_笑顔': '/kiwiSisters/img/shirasagi_smile.png',
      '白鷺_驚き': '/kiwiSisters/img/shirasagi_surprise.png',
      '白鷺_考察': '/kiwiSisters/img/shirasagi_thinking.png',
      '白鷺_怒る': '/kiwiSisters/img/shirasagi_ungry.png',
      '雉真_通常': '/kiwiSisters/img/kijima_chotosmile.png',
      '雉真_怒る': '/kiwiSisters/img/kijima_angry.png',
      '雉真_焦り': '/kiwiSisters/img/kijima_aseri.png',
      '雉真_真顔': '/kiwiSisters/img/kijima_nomal.png',
      '雉真_笑顔': '/kiwiSisters/img/kijima_smile.png',
      '雉真_考察': '/kiwiSisters/img/kijima_thinking.png',
      '鷹森_通常': '/kiwiSisters/img/takamori_nomal.png',
      '鷹森_驚き': '/kiwiSisters/img/takamori_bikkuri.png',
      '鷹森_江永ピンチ': '/kiwiSisters/img/takamori_enagapinch.png',
      '鷹森_戦闘': '/kiwiSisters/img/takamori_kamae.png',
      '鷹森_落胆': '/kiwiSisters/img/takamori_syonbori.png',
      '江永': '/kiwiSisters/img/enaga_standard.png',
      '花子': '/kiwiSisters/img/hanakosan_smile.png',
      'キーウィ・キウイ': '/kiwiSisters/img/kiwi.png',
    };

    let isInitialLoad = true;
    let lastSentBgm = null;
    let lastSentPage = null;
    let allowEnterKey = true;
    let currentData = null;

    async function loadPage(page) {
      currentPage = page;
      // if (!isInitialLoad) {
      //   sessionStorage.setItem("currentPage", page);
      // }
      // isInitialLoad = false;

      const res = await fetch(`/kiwiSisters/controller/getPageData.php?chapter=${sessionStorage.getItem("currentChapter") || 1}&page=${page}`);
      const data = await res.json();
      console.log("🎯 fetch結果 =", data);
      currentData = data;

      const bgmFrame = document.getElementById("bgm-frame");
      const bgmWindow = bgmFrame?.contentWindow;

      let lastTime = 0;
      if (bgmWindow) {
        let effectiveBgm = (data.bgm || "").trim();

        // BGM が変わらなければ送信しない
        const isSameBgm = effectiveBgm === lastSentBgm;

        if (!isSameBgm) {
          const currentTimePromise = new Promise((resolve) => {
            function handler(e) {
              if (e.data?.type === "responseCurrentTime") {
                window.removeEventListener("message", handler);
                resolve(e.data.currentTime);
              }
            }
            window.addEventListener("message", handler);
            bgmWindow.postMessage({ type: "requestCurrentTime" }, "*");
          });

          const lastTime = parseFloat(await currentTimePromise) || 0;

          const currentTime = 0;  // 新しいBGMなら 0 から

          console.log(`🎶 BGM送信: ${effectiveBgm}, 前回送信: ${lastSentBgm}`);

          bgmWindow.postMessage(
            { type: "setBgm", bgm: effectiveBgm, currentTime },
            "*"
          );

          lastSentBgm = effectiveBgm;  // 状態を変数に保存
          lastSentPage = page;
        } else {
          console.log(`⏭️ 同じBGMなので送信省略: ${effectiveBgm}`);
        }
      }

      const charNameEl = document.getElementById("charName");
      const textAreaEl = document.getElementById("textArea");

      charNameEl.innerText = data.character;
      textAreaEl.innerText = data.text;

      const bgMap = { '廊下': '../../img/rouka.png', 'トイレ': '../../img/toire.png', '学校': '../../img/school.png' };
      const bg = bgMap[data.background] || '';
      document.body.style.backgroundImage = `url('${bg}'), linear-gradient(180deg, rgba(98,9,20,0.97) 77.49%, rgba(200,19,40,0.97) 100%)`;

      const charImg = document.getElementById("charImage");
      let imageSrc = charImageMap[data.illustration?.trim()] || "";
      if (!imageSrc && data.illustration) {
        const base = data.illustration.split('_')[0].trim();
        imageSrc = charImageMap[`${base}_通常`] || Object.entries(charImageMap).find(([key]) => key.startsWith(base))?.[1];
      }
      charImg.style.display = imageSrc ? "block" : "none";
      charImg.src = imageSrc || "";
      charImg.alt = data.illustration || "";

      const choiceArea = document.getElementById("choiceArea");
      choiceArea.innerHTML = "";
      const nextButton = document.getElementById("nextButton");

      if (data.next_state == 2) {
        allowEnterKey = false;
        nextButton.disabled = true;
        nextButton.style.display = "none";
        choiceArea.innerHTML = "";

        [data.choice1, data.choice2, data.jumpTarget].forEach(choice => {
          if (choice && /(.+?)\((\d+)\)/.test(choice)) {
            const [, label, pageNum] = choice.match(/(.+?)\((\d+)\)/);
            const btn = document.createElement("button");
            btn.textContent = label;
            btn.onclick = () => loadPage(parseInt(pageNum, 10));
            choiceArea.appendChild(btn);
          }
        });
        choiceArea.style.display = "block";
      } else {
        allowEnterKey = true;
        choiceArea.style.display = "none";
        nextButton.disabled = false;
        nextButton.style.display = "inline-block";
      }
    }

    function handleNext() {
      if (currentData.next_state == 0) {
        window.location.href = "/kiwiSisters/controller/StartMenu.php";
      } else if (currentData.next_state == 3 && currentData.jumpTarget && /^\d+$/.test(currentData.jumpTarget)) {
        const targetPage = parseInt(currentData.jumpTarget, 10);
        loadPage(targetPage);
      } else {
        loadPage(currentPage + 1);
      }
    }

    document.getElementById("saveButton").onclick = () => {
      console.log("[StoryPlayController1.php] セーブボタン押下: currentPage=", currentPage);
      sessionStorage.setItem("currentPage", currentPage);
      sessionStorage.setItem("currentChapter", sessionStorage.getItem("currentChapter") || "1");
      window.location.href = "/kiwiSisters/controller/SaveSelect.php";
    };


    document.getElementById("nextButton").onclick = handleNext;

    window.addEventListener("DOMContentLoaded", () => {
      const chapter = sessionStorage.getItem("currentChapter");
      const page = sessionStorage.getItem("currentPage");

      console.log("[StoryPlayController1.php] DOMContentLoaded");
      console.log("[StoryPlayController1.php] currentChapter from sessionStorage:", chapter);
      console.log("[StoryPlayController1.php] currentPage from sessionStorage:", page);

      if (!chapter) {
        alert("章の選択情報（currentChapter）がありません。章選択画面からやり直してください。");
        return;
      }

      let initialPage = parseInt(page, 10);
      if (isNaN(initialPage) || initialPage < 2) {
        initialPage = 2;
        sessionStorage.setItem("currentPage", "2");
      }

      currentPage = initialPage;
      console.log("[StoryPlayController1.php] currentPage 確定:", currentPage);

      requestAnimationFrame(() => {
        loadPage(currentPage).then(() => {
          console.log("[StoryPlayController1.php] loadPage 完了 - page:", currentPage);
        });
      });
    });


    document.addEventListener("keydown", e => {
      if (e.key === "Enter" && allowEnterKey) {
        handleNext();
      }
    });
  </script>

</body>

</html>