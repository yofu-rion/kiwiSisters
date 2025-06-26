<!DOCTYPE html>
<html lang="en">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// シナリオファイル名
$inputFileName = '../../scenario/ScenarioPlay1.xlsx';

$spreadsheet = IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

// GETパラメータから行番号を取得（デフォルトは1）
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$row = $sheet->getRowIterator($page, $page)->current();
$cellIterator = $row->getCellIterator('A', 'G'); // A列〜G列（1〜7列目）
$cellIterator->setIterateOnlyExistingCells(false);

// 変数に格納
$values = [];
foreach ($cellIterator as $cell) {
    $values[] = $cell->getValue();
}

list($background, $talkingCharacter, $text, $next_state, $character1, $character2, $character3) = $values;

// 背景画像のパスを設定
if ($background === '廊下') {
    $backgroundImage = '../../img/rouka.png';
} elseif($background === 'トイレ') {
    $backgroundImage = '../../img/toire.png';
}


$nextPage = $page + 1;
// ボタンが押されたか判定
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($next_state == 0) {
        // 終了
        header("Location: ../StartMenu.php");
        exit;
    } elseif ($next_state == 1) {
        // 次のページに遷移
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
                linear-gradient(
                    180deg,
                    rgba(98, 9, 20, 0.97) 77.49%,
                    rgba(200, 19, 40, 0.97) 100%
                );
        }
    </style>
</head>

<body>
    <div class="full">
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
                    <!-- <button>ロード</button> -->
                    <a href="/kiwiSisters/controller/StartMenu.php" class="title">タイトル</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>