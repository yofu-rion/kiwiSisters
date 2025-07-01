<?php
$saveData = [];
for ($i = 1; $i <= 4; $i++) {
    $path = "../../save/slot{$i}.json";
    if (file_exists($path)) {
        $json = file_get_contents($path);
        $data = json_decode($json, true);
        $saveData[$i] = $data['page'] ?? null;
    }
}
?>

<div class="save">
    <?php for ($i = 1; $i <= 4; $i++): ?>
        <form method="post" action="DataLoadController.php">
            <input type="hidden" name="slot" value="<?= $i ?>">
            <button type="submit" class="save-block">
                <?= $i ?> <?= isset($saveData[$i]) ? "(Page {$saveData[$i]})" : '' ?>
            </button>
        </form>
    <?php endfor; ?>
</div>
