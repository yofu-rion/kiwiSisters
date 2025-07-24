<?php
function listFiles($dir, $exts = []) {
  $files = [];
  if (!is_dir($dir)) return $files;

  foreach (scandir($dir) as $file) {
    if ($file === '.' || $file === '..') continue;
    $path = "$dir/$file";
    if (is_file($path)) {
      $ext = pathinfo($file, PATHINFO_EXTENSION);
      if (empty($exts) || in_array(strtolower($ext), $exts)) {
        $files[] = $path;
      }
    }
  }
  return $files;
}

header('Content-Type: application/json');

echo json_encode([
  'images' => listFiles('img', ['png', 'jpg', 'jpeg', 'gif', 'webp']),
  'sounds' => array_merge(
    listFiles('se', ['mp3', 'ogg', 'wav']),
    listFiles('music', ['mp3', 'ogg'])
  ),
  'scenarios' => listFiles('scenario', ['csv', 'json', 'txt']),
]);
