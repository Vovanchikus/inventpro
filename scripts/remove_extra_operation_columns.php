<?php

$host = '127.0.0.1';
$db   = 'inventpro-test';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$drop = ['doc_name','doc_num','doc_date','counteragent'];

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    foreach ($drop as $col) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->execute([$db, 'samvol_inventory_operations', $col]);
        $exists = (bool) $stmt->fetchColumn();
        if (!$exists) {
            echo "SKIP: $col not found\n";
            continue;
        }
        try {
            $pdo->exec("ALTER TABLE samvol_inventory_operations DROP COLUMN $col");
            echo "DROPPED: $col\n";
        } catch (PDOException $e) {
            echo "ERROR dropping $col: " . $e->getMessage() . "\n";
        }
    }

    // ensure created_at and updated_at exist
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
    foreach (['created_at','updated_at'] as $col) {
        $stmt->execute([$db, 'samvol_inventory_operations', $col]);
        $exists = (bool) $stmt->fetchColumn();
        if (!$exists) {
            try {
                if ($col === 'created_at' || $col === 'updated_at') {
                    $pdo->exec("ALTER TABLE samvol_inventory_operations ADD COLUMN $col TIMESTAMP NULL");
                    echo "ADDED: $col\n";
                }
            } catch (PDOException $e) {
                echo "ERROR adding $col: " . $e->getMessage() . "\n";
            }
        } else {
            echo "OK: $col exists\n";
        }
    }

    // print final column list
    $stmt = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = 'samvol_inventory_operations' ORDER BY ORDINAL_POSITION");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "\nColumns now:\n";
    foreach ($cols as $c) echo " - $c\n";

} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
