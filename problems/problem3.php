<?php
function tryRecoverMojibake($mojibake) {
    $encodings = [['from' => 'SJIS', 'to' => 'UTF-8']    ];

    echo "元の文字列: $mojibake\n\n";

    foreach ($encodings as $pair) {
        $converted = @mb_convert_encoding($mojibake, $pair['to'], $pair['from']);
        echo "[from {$pair['from']} → to {$pair['to']}]：$converted\n";
    }
}

$mojibake = "縺ｫ縺偵ｍ縺｣縺､縺｣縺溘ｍ";

tryRecoverMojibake($mojibake);
?>
