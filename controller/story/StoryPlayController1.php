<!DOCTYPE html>
<html lang="en">

<?php
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

if ($background === '廊下') {
    $backgroundImage = '../../img/rouka.png';
} elseif ($background === 'トイレ') {
    $backgroundImage = '../../img/toire.png';
}

$charImageMap = [
    '白鷺' => '/kiwiSisters/img/shirasagi_standard.png',
    '雉真' => '/kiwiSisters/img/kijima_standard.png',
    // 必要に応じてここへ追加
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
        // 選択肢画面に遷移
        exit;
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
                    <a href="/kiwiSisters/controller/story/Save.php" class="save">セーブ</a>
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