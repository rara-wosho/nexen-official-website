<?php

// // OLD LOCAL CONNECTION - COMMENTED OUT
$host = "localhost";
$dbname = "ojt-website";
$dbUser = "postgres";
$dbPass = "4dm1n";
$port = "5432";
//

try {
    $connection = new PDO("pgsql:dbname='$dbname';port='$port';host='$host'", $dbUser, $dbPass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// NEON DATABASE CONNECTION
// $neon_host = "ep-dark-tree-a1rdtl53-pooler.ap-southeast-1.aws.neon.tech";
// $neon_dbname = "neondb";
// $neon_user = "neondb_owner";
// $neon_password = "npg_nDANz6LFWSa8";
// $neon_port = "5432";

// try {
//     // Neon requires the endpoint ID in options (SNI workaround) when libpq does not provide SNI automatically.
//     // endpoint ID is the first part of your host, e.g. "ep-red-mode-a1tij7a5".
//     $endpoint_id = "ep-dark-tree-a1rdtl53";
//     // $dsn = "pgsql:host=$neon_host;port=$neon_port;dbname=$neon_dbname;sslmode=require;options='--endpoint=$endpoint_id'";

//     // Alternative using URL-encoded options parameter (depending on libpq version):
//     $dsn = "pgsql:host=$neon_host;port=$neon_port;dbname=$neon_dbname;sslmode=require;options=endpoint%3D$endpoint_id";

//     $connection = new PDO($dsn, $neon_user, $neon_password);
//     $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }
