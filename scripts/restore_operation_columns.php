<?php

$host = '127.0.0.1';
$db   = 'inventpro-test';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$columns = [
    "doc_name VARCHAR(255) NULL",
    "doc_num VARCHAR(255) NULL",
    "doc_date DATE NULL",
    "is_draft TINYINT(1) NOT NULL DEFAULT 0",
    "is_posted TINYINT(1) NOT NULL DEFAULT 0",
    "note_id INT UNSIGNED NULL",
    "slug VARCHAR(255) NULL",
    "counteragent VARCHAR(255) NULL",
    "draft_products TEXT NULL",
];

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    foreach ($columns as $colDef) {
        // extract column name
        if (!preg_match('/^([a-z_]+)\s/i', $colDef, $m)) {
            continue;
        }
        $col = $m[1];
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->execute([$db, 'samvol_inventory_operations', $col]);
        $exists = (bool) $stmt->fetchColumn();
        if ($exists) {
            echo "SKIP: $col exists\n";
            continue;
        }

        $sql = "ALTER TABLE samvol_inventory_operations ADD COLUMN $colDef";
        try {
            $pdo->exec($sql);
            echo "ADDED: $col\n";
        } catch (PDOException $e) {
            echo "ERROR adding $col: " . $e->getMessage() . "\n";
        }
    }

    // add index for note_id if column exists and index not exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
    $stmt->execute([$db, 'samvol_inventory_operations', 'note_id']);
    $hasIndex = (bool)$stmt->fetchColumn();
    if (!$hasIndex) {
        try {
            $pdo->exec("ALTER TABLE samvol_inventory_operations ADD INDEX (note_id)");
            echo "ADDED: index note_id\n";
        } catch (PDOException $e) {
            echo "ERROR adding index note_id: " . $e->getMessage() . "\n";
        }
    } else {
        echo "SKIP: index note_id exists\n";
    }

} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
