<?php

require_once 'db_connect.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "UPDATE demo_bookings SET is_read = true WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->execute([':id' => $id]);
    header("Location: bookings.php");
    exit();
}
