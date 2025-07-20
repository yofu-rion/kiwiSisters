<?php
session_start();

if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

// POST からデータを取得
$page = isset($_POST['page']) ? (int)$_POST['page'] : 2;
$chapter = isset($_POST['chapter']) ? (int)$_POST['chapter'] : 1;
$bgm = isset($_POST['bgm']) ? $_POST['bgm'] : '';

// $_SESSION に保存してからリダイレクト
$_SESSION['nextPageAfterUpload'] = $page;
$_SESSION['chapterAfterUpload'] = $chapter;
// BGM を使う場合はここで bgm も保存してよい
// $_SESSION['bgmAfterUpload'] = $bgm;

header("Location: /kiwiSisters/controller/story/StoryPlayController{$chapter}.php");
exit;

