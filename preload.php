<?php
function listFiles($dir, $exts = []) {
  $files = [];
  foreach (scandir($dir) as $file) {
    if ($file === '.' || $file === '..') continue;
    $path = "$dir/$file";
    if (is_file($path)) {
      $ext = pathinfo($file, PATHINFO_EXTENSION);
      if (empty($exts) || in_array(strtolower($ext), $exts)) {
        $files[] = '/' . ltrim($path, '/');
      }
    } elseif (is_dir($path)) {
      $files = array_merge($files, listFiles($path, $exts));
    }
  }
  return $files;
}

header('Content-Type: application/json');

$imgFiles = listFiles('img', ['png', 'jpg', 'jpeg', 'gif', 'webp']);
$seFiles = listFiles('se', ['mp3', 'ogg', 'wav']);
$scenarioFiles = listFiles('scenario', ['csv', 'json', 'txt']);
$controllerFiles = listFiles('controller', ['php']);

echo json_encode([
  'images' => $imgFiles,
  'sounds' => $seFiles,
  'scenarios' => $scenarioFiles,
  'controllers' => $controllerFiles,
]);
