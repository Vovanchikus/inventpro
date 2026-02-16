<?php

$host = '127.0.0.1';
$db   = 'inventpro-test';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = "ALTER TABLE samvol_inventory_operations ADD COLUMN draft_products TEXT NULL";
    $pdo->exec($sql);
    echo "OK: column added\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
