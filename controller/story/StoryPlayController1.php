<!DOCTYPE html>
<html lang="en">

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

if ($background === '廊下') {
    $backgroundImage = '../../img/rouka.png';
} elseif ($background === 'トイレ') {
    $backgroundImage = '../../img/toire.png';
}

$charImageMap = [
    '白鷺' => '/kiwiSisters/img/shirasagi_standard.png',
    '雉真' => '/kiwiSisters/img/kijima_chotosmile.png',
    '鷹森' => '/kiwiSisters/img/takamori_standard.png',
    '江永' => '/kiwiSisters/img/enaga_standard.png',
    '花子' => '/kiwiSisters/img/hanakosan_smile.png',
    'キーウィ・キウイ' => '/kiwiSisters/img/kiwi.png',
];

$charImageFile = $charImageMap[$illustration] ?? null;
$nextPage = $page + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($next_state == 0) {
        header("Location: ../StartMenu.php");
        exit;
    } elseif ($next_state == 1) {
        header("Location: StoryPlayController1.php?page={$nextPage}");
        exit;
    } elseif ($next_state == 2) {
        if (isset($_POST['choice'])) {
            $targetPage = (int) $_POST['choice'];
            header("Location: StoryPlayController1.php?page=$targetPage");
            exit;
        }
    } elseif ($next_state == 3) {
        if (is_numeric($jumpTarget)) {
            header("Location: StoryPlayController1.php?page=$jumpTarget");
            exit;
        }
    } elseif ($next_state == 4) {
        // ファイルアップロード・ダウンロード画面を表示（分岐なしで止まる）
    }

}

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
    <div class="full">
        <?php if ($charImageFile): ?>
            <img class="char-stand" src="<?= htmlspecialchars($charImageFile) ?>"
                alt="<?= htmlspecialchars($illustration) ?>">
        <?php endif; ?>
        <?php if ($next_state == 2): ?>
            <form method="post" class="choices">
                <?php if ($choice1 && preg_match('/(.+?)\((\d+)\)/', $choice1, $match1)): ?>
                    <button type="submit" name="choice" value="<?= $match1[2] ?>">
                        <?= htmlspecialchars($match1[1]) ?>
                    </button>
                <?php endif; ?>
                <?php if ($choice2 && preg_match('/(.+?)\((\d+)\)/', $choice2, $match2)): ?>
                    <button type="submit" name="choice" value="<?= $match2[2] ?>">
                        <?= htmlspecialchars($match2[1]) ?>
                    </button>
                <?php endif; ?>
                <?php if ($jumpTarget && preg_match('/(.+?)\((\d+)\)/', $jumpTarget, $match3)): ?>
                    <button type="submit" name="choice" value="<?= $match3[2] ?>">
                        <?= htmlspecialchars($match3[1]) ?>
                    </button>
                <?php endif; ?>

            </form>
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
        <?php else: ?>
            <!-- 通常のセリフ表示と次へボタン -->
        <?php endif; ?>


        <div class="kuuhaku">a</div>
        <div class="comment">
            <div class="hako">
                <div class="name"><?php echo htmlspecialchars($talkingCharacter); ?></div>
                <div class="text">
                    <div><?php echo htmlspecialchars($text); ?></div>
                    <form method="post">
                        <button class="next">></button>
                    </form>
                </div>
                <div class="menu">
                    <a href="/kiwiSisters/controller/SaveSelect.php?page=<?= $page ?>" class="save">セーブ</a>
                    <a href="/kiwiSisters/controller/StartMenu.php" class="title">タイトル</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form");
            document.addEventListener("keydown", (e) => {
                if (e.key === "Enter" && form) {
                    e.preventDefault();
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>