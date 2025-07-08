<!DOCTYPE html>
<html lang="en">

<?php
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page === 1) {
    header("Location: StoryPlayController1.php?page=2&chapter=1");
    exit;
}
?>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileName = '../../scenario/ScenarioPlay1.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$row = $sheet->getRowIterator($page, $page)->current();

$cellIterator = $row->getCellIterator();
$cellIterator->setIterateOnlyExistingCells(false);

$values = [];
foreach ($cellIterator as $cell) {
    $values[] = $cell->getValue();
}

session_start();

$background = $values[0] ?? '';
$talkingCharacter = $values[1] ?? '';
$text = $values[2] ?? '';
$next_state = $values[3] ?? '';
$illustration = $values[4] ?? '';
$choice1 = $values[9] ?? '';
$choice2 = $values[10] ?? '';
$jumpTarget = $values[11] ?? '';
$correctjumpTarget = $values[12] ?? '';
$incorrectjumpTarget = $values[13] ?? '';
$bgmRaw = $values[14] ?? '';

if ($background === '廊下') {
    $backgroundImage = '../../img/rouka.png';
} elseif ($background === 'トイレ') {
    $backgroundImage = '../../img/toire.png';
} elseif ($background === '学校') {
    $backgroundImage = '../../img/school.png';
}

$charImageMap = [
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
    'キーウィ・キウイ' => '/kiwiSisters/img/kiwi.png',
];

$bgmMap = [
    '探索' => 'tansaku.mp3',
    '探索_不穏' => 'tansaku_fuon.mp3',
    '静止' => null,
];

if ($bgmRaw === '静止') {
    $bgmFile = null;
    $_SESSION['lastBgm'] = null;
} elseif (trim($bgmRaw) !== '') {
    $bgmFile = $bgmMap[trim($bgmRaw)] ?? null;
    $_SESSION['lastBgm'] = $bgmFile;
} else {
    $bgmFile = $_SESSION['lastBgm'] ?? null;
}

$illustration = (string) $illustration;

if (isset($charImageMap[$illustration])) {
    $charImageFile = $charImageMap[$illustration];
} elseif (strpos($illustration, '_') !== false) {
    $baseName = explode('_', $illustration)[0];
    foreach ($charImageMap as $key => $path) {
        if (strpos($key, $baseName) === 0) {
            $charImageFile = $path;
            break;
        }
    }
} else {
    $charImageFile = null;
}

$nextPage = $page + 1;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/story/play1.css">
    <style>
        body {
            background-image: url('<?php echo $backgroundImage; ?>'),
                linear-gradient(180deg,
                    rgba(98, 9, 20, 0.97) 77.49%,
                    rgba(200, 19, 40, 0.97) 100%);
        }
    </style>
</head>

<body>
    <!-- BGM制御のiframe（StoryPlayController内で） -->
    <iframe id="bgm-frame" src="/kiwiSisters/controller/story/bgm.html" style="display: none;"
        allow="autoplay"></iframe>

    <div class="full">
        <?php if ($charImageFile): ?>
            <img class="char-stand" src="<?= htmlspecialchars($charImageFile) ?>"
                alt="<?= htmlspecialchars($illustration) ?>">
        <?php endif; ?>
        <?php if ($next_state == 2): ?>
            <div class="choices">
                <?php if ($choice1 && preg_match('/(.+?)\((\d+)\)/', $choice1, $match1)): ?>
                    <form method="get" action="StoryPlayController1.php">
                        <input type="hidden" name="page" value="<?= $match1[2] ?>">
                        <button type="submit"><?= htmlspecialchars($match1[1]) ?></button>
                    </form>
                <?php endif; ?>
                <?php if ($choice2 && preg_match('/(.+?)\((\d+)\)/', $choice2, $match2)): ?>
                    <form method="get" action="StoryPlayController1.php">
                        <input type="hidden" name="page" value="<?= $match2[2] ?>">
                        <button type="submit"><?= htmlspecialchars($match2[1]) ?></button>
                    </form>
                <?php endif; ?>
                <?php if ($jumpTarget && preg_match('/(.+?)\((\d+)\)/', $jumpTarget, $match3)): ?>
                    <form method="get" action="StoryPlayController1.php">
                        <input type="hidden" name="page" value="<?= $match3[2] ?>">
                        <button type="submit"><?= htmlspecialchars($match3[1]) ?></button>
                    </form>
                <?php endif; ?>
            </div>
        <?php elseif ($next_state == 4): ?>
            <div class="file-section">
                <form action="download1.php" method="get" class="file-download">
                    <button type="submit">ファイルをダウンロード</button>
                </form>
                <form action="upload1.php" method="post" enctype="multipart/form-data" class="file-upload">
                    <input type="file" name="uploaded_file" accept=".php" required>
                    <input type="hidden" name="correctjumpTarget" value="<?php echo $correctjumpTarget; ?>">
                    <input type="hidden" name="incorrectjumpTarget" value="<?php echo $incorrectjumpTarget; ?>">
                    <button type="submit">ファイルをアップロード</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="kuuhaku">a</div>
        <div class="comment">
            <div class="hako">
                <div class="name"><?php echo htmlspecialchars($talkingCharacter); ?></div>
                <div class="text">
                    <div><?php echo htmlspecialchars($text); ?></div>
                    <form id="nextForm" method="get" action="StoryPlayController1.php">
                        <input type="hidden" name="page" value="<?= $nextPage ?>">
                        <button id="nextButton" class="next">></button>
                    </form>
                </div>
                <div class="menu">
                    <a href="/kiwiSisters/controller/SaveSelect.php?page=<?= $page ?>&chapter=1" class="save">セーブ</a>
                    <a href="#" class="title"
                        onclick="window.top.location.href='/kiwiSisters/controller/StartMenu.php'; return false;">タイトル</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const saveBgmTime = () => {
            const bgmFrame = window.top.document.getElementById("bgm-frame");
            const bgmWindow = bgmFrame?.contentWindow;
            if (bgmWindow) {
                bgmWindow.postMessage({ type: "requestCurrentTime" }, "*");
            }
        };

        window.addEventListener("DOMContentLoaded", () => {
            const bgmFile = <?= json_encode($bgmFile) ?>;

            const trySendBgmToIframe = () => {
                const bgmFrame = window.top.document.getElementById("bgm-frame");
                const bgmWindow = bgmFrame?.contentWindow;

                if (!bgmWindow) {
                    console.warn("BGM iframe not ready. Retrying...");
                    if ((window._bgmRetryCount || 0) < 5) {
                        window._bgmRetryCount = (window._bgmRetryCount || 0) + 1;
                        setTimeout(trySendBgmToIframe, 300);
                    }
                    return;
                }

                const muted = localStorage.getItem("volumeMuted") === "true";

                if (muted || bgmFile === null) {
                    console.log("🔇 Sending null to BGM iframe");
                    bgmWindow.postMessage({ bgm: null }, "*");
                    sessionStorage.setItem("lastBgm", "");
                    return;
                }

                const lastBgm = sessionStorage.getItem("lastBgm");
                const lastTime = parseFloat(sessionStorage.getItem("bgmTime") || "0");
                const currentTime = (lastBgm === bgmFile) ? lastTime : 0;

                console.log("🎵 Sending BGM to iframe:", bgmFile, "at", currentTime, "sec");
                bgmWindow.postMessage({ bgm: bgmFile, currentTime }, "*");
                sessionStorage.setItem("lastBgm", bgmFile);
            };

            setTimeout(trySendBgmToIframe, 500);
        });

        const nextButton = document.getElementById("nextButton");
        const nextForm = document.getElementById("nextForm");

        if (nextButton && nextForm) {
            nextButton.addEventListener("click", (e) => {
                e.preventDefault();
                saveBgmTime();
                setTimeout(() => nextForm.submit(), 50);
            });
        }

        document.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && nextForm) {
                e.preventDefault();
                saveBgmTime();
                setTimeout(() => nextForm.submit(), 50);
            }
        });
    </script>
</body>

</html>