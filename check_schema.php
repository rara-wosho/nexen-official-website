<?php
require_once 'db_connect.php';
try {
    $stmt = $connection->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'departments'");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns in departments: " . implode(", ", $columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
