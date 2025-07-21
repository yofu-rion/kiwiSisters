<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$chapter = isset($_GET['chapter']) ? (int) $_GET['chapter'] : 1;
$page = isset($_GET['page']) ? max(2, (int) $_GET['page']) : 2;

error_log("=== getPageData called ===");
error_log("chapter=$chapter page=$page");

$inputFileName = realpath("../scenario/ScenarioPlay{$chapter}.xlsx");
error_log("resolved path=$inputFileName");

if (!$inputFileName || !file_exists($inputFileName)) {
    error_log("❌ File not found: $inputFileName");
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(["error" => "File not found", "path" => $inputFileName]);
    exit;
}

try {
    $spreadsheet = IOFactory::load($inputFileName);
    $sheet = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestRow();
    error_log("highestRow=$highestRow");

    if ($page > $highestRow) {
        error_log("❗ page=$page exceeds highestRow=$highestRow");
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
        $v = (string) $cell->getValue();
        $values[] = $v;
        error_log("cell value: '$v'");
    }

    $response = [
        'background' => $values[0] ?? '',
        'character' => $values[1] ?? '',
        'text' => $values[2] ?? '',
        'next_state' => $values[3] ?? '',
        'illustration' => $values[4] ?? '',
        'illustration2' => $values[5] ?? '',
        'illustration3' => $values[6] ?? '',
        'illustration4' => $values[7] ?? '',
        // 'illustration5' => $values[8] ?? '',
        'choice1' => $values[9] ?? '',
        'choice2' => $values[10] ?? '',
        'choice3' => $values[8] ?? '',
        'jumpTarget' => $values[11] ?? '',
        'correctjumpTarget' => $values[12] ?? '',
        'incorrectjumpTarget' => $values[13] ?? '',
        'bgm' => $values[14] ?? '',
        'se' => $values[15] ?? '',
        'end' => $values[16] ?? '',
    ];

    error_log("✅ Response JSON: " . json_encode($response));

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (Throwable $e) {
    error_log("🔥 Exception: " . $e->getMessage());
    error_log($e->getTraceAsString());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error" => $e->getMessage(),
        "trace" => $e->getTraceAsString(),
        "file" => $inputFileName
    ]);
}
