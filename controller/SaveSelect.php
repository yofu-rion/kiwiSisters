<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ログイン確認
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

// データベース接続
$pdo = new PDO(
    'mysql:host=localhost;dbname=kiwi_datas;charset=utf8',
    'staff',
    'password'
);

function loadSlotData($slotNumber, $pdo, $username)
{
  try {
    $sql = $pdo->prepare('SELECT page, timestamp FROM save_data WHERE user_name = ? AND slot_num = ?');
    $sql->execute([$username, $slotNumber]);
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
      return [
        'page' => $result['page'],
        'timestamp' => $result['timestamp']
      ];
    }
    return null;
  } catch (PDOException $e) {
    error_log('セーブデータ読み込みエラー: ' . $e->getMessage());
    return null;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>セーブスロット選択</title>
  <link rel="stylesheet" href="../css/save_select.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="container">
    <h1>セーブするスロットを選んでください</h1>
    <ul class="slot-list">
      <?php for ($i = 1; $i <= 4; $i++): ?>
        <?php $data = loadSlotData($i, $pdo, $username); ?>
        <li>
          <?php if ($data): ?>
            <?php
            $timestamp = isset($data['timestamp']) ? htmlspecialchars($data['timestamp']) : '未保存';
            $pageNumber = isset($data['page']) ? htmlspecialchars((string) $data['page']) : '?';
            ?>
            <div class="slot-info">スロット<?= $i ?>：<?= $timestamp ?> に Page <?= $pageNumber ?> を保存済み</div>
          <?php else: ?>
            <div class="slot-info">スロット<?= $i ?>：空</div>
          <?php endif; ?>

          <a class="save-button" href="Save.php?slot=<?= $i ?>&page=<?= $page ?>">スロット<?= $i ?>にセーブ</a>
        </li>
      <?php endfor; ?>
    </ul>
    <a class="back-link" href="javascript:history.back()">戻る</a>
  </div>
</body>

</html>