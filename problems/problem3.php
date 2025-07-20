<?php
function tryRecoverMojibake() {
    $mojibake = "縺ｫ縺偵ｍ縺｣縺､縺｣縺溘ｍ";
    $encodings = [['from' => 'UTF-8', 'to' => 'SJIS']];

    echo "元の文字列: $mojibake\n\n";

    foreach ($encodings as $pair) {
        echo "[from {$pair['from']} → to {$pair['to']}] を試しました\n";
    }

    $isCorrect = false;
    foreach ($encodings as $pair) {
        if ($pair['from'] === 'SJIS' && $pair['to'] === 'UTF-8') {
            $isCorrect = true;
        }
    }

    if ($isCorrect) {
        echo "OK";
        global $status;
        $status = "ok";
    } else {
        echo "NG";
    }
}

tryRecoverMojibake();
