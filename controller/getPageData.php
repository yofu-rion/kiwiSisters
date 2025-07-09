<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php'; // ← 相対パスを修正（controller配下からの相対）
use PhpOffice\PhpSpreadsheet\IOFactory;

$chapter = isset($_GET['chapter']) ? (int) $_GET['chapter'] : 1;
$page = isset($_GET['page']) ? max(2, (int) $_GET['page']) : 2;

$inputFileName = realpath("../scenario/ScenarioPlay{$chapter}.xlsx");

if (!$inputFileName || !file_exists($inputFileName)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(["error" => "File not found", "path" => $inputFileName]);
    exit;
}

try {
    $spreadsheet = IOFactory::load($inputFileName);
    $sheet = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestRow();
    if ($page > $highestRow) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(["error" => "Page number $page exceeds max row $highestRow"]);
        exit;
    }

    $row = $sheet->getRowIterator($page, $page)->current();
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    $values = [];
    foreach ($cellIterator as $cell) {
        $values[] = (string) $cell->getValue(); // ここで明示的に文字列へ
    }

    header('Content-Type: application/json');
    echo json_encode([
        'background' => $values[0] ?? '',
        'character' => $values[1] ?? '',
        'text' => $values[2] ?? '',
        'next_state' => $values[3] ?? '',
        'illustration' => $values[4] ?? '',
        'choice1' => $values[9] ?? '',
        'choice2' => $values[10] ?? '',
        'jumpTarget' => $values[11] ?? '',
        'correctjumpTarget' => $values[12] ?? '',
        'incorrectjumpTarget' => $values[13] ?? '',
        'bgm' => $values[14] ?? '',
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => $e->getMessage(),
        "trace" => $e->getTraceAsString(),
        "file" => $inputFileName
    ]);
}
