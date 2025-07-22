<?php
session_start();

// ログイン確認
if (!isset($_SESSION['login'])) {
    header('Location: ../../index.php');
    exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['login']['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];
    $filename = basename($file['name']);

    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
        echo "PHPファイルのみアップロード可能です。";
        exit;
    }

    $correctjumpTarget = $_POST['correctjumpTarget'] ?? 1;
    $incorrectjumpTarget = $_POST['incorrectjumpTarget'] ?? 1;
    $chapter = $_POST['chapter'] ?? 1;

    $code = file_get_contents($file['tmp_name']);
    $code = '?>' . $code . '<?php ';
    eval ($code);

    if ($doorState === "open") {
        $nextPage = $correctjumpTarget;
        
        // データベース接続してprogressを更新
        try {
            $pdo = new PDO(
                'mysql:host=127.0.0.1;dbname=kiwi_datas;charset=utf8',
                'staff',
                'password'
            );
            
            // 現在のprogressを取得
            $selectSql = $pdo->prepare('SELECT progress FROM login WHERE name = ?');
            $selectSql->execute([$username]);
            $currentProgress = $selectSql->fetchColumn();
            
            // progressが2の倍数でなければ2を掛ける
            if ($currentProgress % 2 !== 0) {
                $newProgress = $currentProgress * 2;
                $updateSql = $pdo->prepare('UPDATE login SET progress = ? WHERE name = ?');
                $updateSql->execute([$newProgress, $username]);
            }
            
        } catch (PDOException $e) {
            error_log('Progress更新エラー: ' . $e->getMessage());
        }
    } else {
        $nextPage = $incorrectjumpTarget;
    }

    // ⭐️ sessionStorage に反映するため session に保存
    $_SESSION['nextPageAfterUpload'] = $nextPage;
    $_SESSION['chapterAfterUpload'] = $chapter;

    header("Location: /kiwiSisters/controller/story/StoryPlayController1.php?fromUpload=1");
    exit;
} else {
    echo "ファイルが選択されていません。";
}
