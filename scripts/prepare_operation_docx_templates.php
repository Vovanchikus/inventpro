<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$templatesDir = __DIR__ . '/../storage/app/templates/operations';

if (!is_dir($templatesDir)) {
    fwrite(STDERR, "Templates directory not found: {$templatesDir}" . PHP_EOL);
    exit(1);
}

$docxFiles = glob($templatesDir . '/*.docx') ?: [];
if (!$docxFiles) {
    fwrite(STDOUT, "No DOCX files found in {$templatesDir}" . PHP_EOL);
    exit(0);
}

$placeholders = [
    '${row_no}',
    '${row_name}',
    '${row_quantity}',
    '${row_unit}',
    '${row_price}',
    '${row_sum}',
    '${row_inv_number}',
];

$force = in_array('--force', $argv ?? [], true);

foreach ($docxFiles as $filePath) {
    $zip = new ZipArchive();
    if ($zip->open($filePath) !== true) {
        fwrite(STDERR, "[ERROR] Cannot open: {$filePath}" . PHP_EOL);
        continue;
    }

    $xml = $zip->getFromName('word/document.xml');
    if ($xml === false) {
        fwrite(STDERR, "[ERROR] word/document.xml not found: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    if (!$force && str_contains($xml, '${row_no}')) {
        fwrite(STDOUT, "[SKIP] Already prepared: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    $loaded = @$dom->loadXML($xml);

    if (!$loaded) {
        fwrite(STDERR, "[ERROR] Invalid XML in: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

    $tables = $xpath->query('//w:tbl');
    if (!$tables || $tables->length === 0) {
        fwrite(STDERR, "[WARN] No tables found, skipped: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    $tableNode = null;
    $dataRow = null;
    $cells = null;
    $bestCellsCount = 0;

    foreach ($tables as $candidateTable) {
        $rows = $xpath->query('./w:tr', $candidateTable);
        if (!$rows || $rows->length < 2) {
            continue;
        }

        $candidateDataRow = $rows->item(1);
        $candidateCells = $xpath->query('./w:tc', $candidateDataRow);
        $candidateCellsCount = $candidateCells ? $candidateCells->length : 0;

        if ($candidateCellsCount > $bestCellsCount) {
            $bestCellsCount = $candidateCellsCount;
            $tableNode = $candidateTable;
            $dataRow = $candidateDataRow;
            $cells = $candidateCells;
        }
    }

    if (!$tableNode || !$dataRow || !$cells || $cells->length === 0) {
        fwrite(STDERR, "[WARN] Suitable table with data row not found, skipped: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    $cellsCount = $cells->length;
    $placeholdersByCellsCount = [
        1 => ['${row_name}'],
        2 => ['${row_no}', '${row_name}'],
        3 => ['${row_no}', '${row_name}', '${row_quantity}'],
        4 => ['${row_no}', '${row_name}', '${row_quantity}', '${row_price}'],
        5 => ['${row_no}', '${row_name}', '${row_quantity}', '${row_price}', '${row_sum}'],
        6 => ['${row_no}', '${row_name}', '${row_quantity}', '${row_unit}', '${row_price}', '${row_sum}'],
    ];

    $activePlaceholders = $placeholdersByCellsCount[$cellsCount] ?? $placeholders;

    for ($i = 0; $i < $cellsCount; $i++) {
        $cell = $cells->item($i);
        if (!$cell) {
            continue;
        }

        $placeholder = $activePlaceholders[$i] ?? '${row_name}';

        while ($cell->firstChild) {
            $cell->removeChild($cell->firstChild);
        }

        $paragraph = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:p');
        $run = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:r');
        $text = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:t');
        $text->nodeValue = $placeholder;

        $run->appendChild($text);
        $paragraph->appendChild($run);
        $cell->appendChild($paragraph);
    }

    $updatedXml = $dom->saveXML();
    if ($updatedXml === false) {
        fwrite(STDERR, "[ERROR] Failed to serialize XML: {$filePath}" . PHP_EOL);
        $zip->close();
        continue;
    }

    $zip->addFromString('word/document.xml', $updatedXml);
    $zip->close();

    fwrite(STDOUT, "[OK] Prepared: {$filePath}" . PHP_EOL);
}

fwrite(STDOUT, "Done." . PHP_EOL);
