<!-- Saveだよ -->
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 未ログインならリダイレクト
if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

// パラメータ取得
$slot = isset($_GET['slot']) ? intval($_GET['slot']) : 0;
$pageHash = $_GET['page'] ?? '';
$chapterHash = $_GET['chapter'] ?? '';
$bgm = isset($_GET['bgm']) ? trim($_GET['bgm']) : '';
$background = isset($_GET['background']) ? trim($_GET['background']) : '';


// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];
error_log("DEBUG: Save.php username=$username");

require '../vendor/autoload.php';
use Hashids\Hashids;

$hashids = new Hashids($username, 8);
error_log("DEBUG: Save.php pageHash=" . ($_GET['page'] ?? 'null'));
error_log("DEBUG: Save.php chapterHash=" . ($_GET['chapter'] ?? 'null'));

if ($slot < 1 || $slot > 4) {
  echo "スロット番号が不正です。";
  exit;
}

// DB接続
$pdo = new PDO(
  'mysql:host=localhost;dbname=kiwi_datas;charset=utf8',
  'staff',
  'password'
);

$pageDecoded = $hashids->decode($pageHash);
$chapterDecoded = $hashids->decode($chapterHash);

error_log("DEBUG: Save.php decoded page=" . var_export($pageDecoded, true));
error_log("DEBUG: Save.php decoded chapter=" . var_export($chapterDecoded, true));

$page = $pageDecoded[0] ?? 2;
$chapter = $chapterDecoded[0] ?? 1;


try {
  // 既存データ削除
  $deleteSql = $pdo->prepare('DELETE FROM save_data WHERE user_name = ? AND slot_num = ?');
  $deleteSql->execute([$username, $slot]);

  error_log("DEBUG: Save.php decoded page=" . var_export($pageDecoded, true));
  error_log("DEBUG: Save.php decoded chapter=" . var_export($chapterDecoded, true));


  // 新規セーブ
  $insertSql = $pdo->prepare('
  INSERT INTO save_data (user_name, slot_num, page, chapter, bgm, background, timestamp)
  VALUES (?, ?, ?, ?, ?, ?, NOW())
');
  $insertSql->execute([$username, $slot, $page, $chapter, $bgm, $background]);


  $saveSuccess = true;
} catch (PDOException $e) {
  $saveSuccess = false;
  error_log('セーブエラー: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>セーブ完了</title>
  <link rel="stylesheet" href="../css/save_select.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="container">
    <?php if ($saveSuccess): ?>
      <p class="success-message">スロット<?= htmlspecialchars($slot) ?>にセーブしました</p>
    <?php else: ?>
      <p class="error-message">セーブに失敗しました。再試行してください。</p>
    <?php endif; ?>
  </div>

  <script>
    const savedPage = "<?= htmlspecialchars($page) ?>";
    const savedChapter = "<?= htmlspecialchars($chapter) ?>";
    const savedBgm = "<?= htmlspecialchars($bgm) ?>";

    sessionStorage.setItem("currentPage", savedPage);
    sessionStorage.setItem("currentChapter", savedChapter);
    sessionStorage.setItem("lastBgm", savedBgm);
    const savedBackground = "<?= htmlspecialchars($background) ?>";
    sessionStorage.setItem("currentBackground", savedBackground);

    setTimeout(() => {
      window.location.href = `/controller/story/StoryPlayController${savedChapter}.php`;
    }, 1000);

  </script>
</body>

</html>