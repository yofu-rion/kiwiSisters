<?php
session_start();

if (!isset($_SESSION['login'])) {
  header('Location: ../index.php');
  exit;
}

$page = isset($_POST['page']) ? (int)$_POST['page'] : 2;
$chapter = isset($_POST['chapter']) ? (int)$_POST['chapter'] : 1;
$bgm = isset($_POST['bgm']) ? $_POST['bgm'] : '';

$_SESSION['nextPageAfterUpload'] = $page;
$_SESSION['chapterAfterUpload'] = $chapter;

header("Location: /controller/story/StoryPlayController{$chapter}.php");
exit;

