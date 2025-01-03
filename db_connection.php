<?php
$env = include 'env.php';

// Access the environment variables
$postgresUser = $env['POSTGRES_USER'];
$postgresPassword = $env['POSTGRES_PASSWORD'];
$postgresDb = $env['POSTGRES_DB'];
$postgresHost = $env['POSTGRES_HOST'];
$postgresPort = $env['POSTGRES_PORT'];

try {
    // Create a new PDO connection
    $dsn = "pgsql:host=$postgresHost;port=$postgresPort;dbname=$postgresDb";
    $conn = new PDO($dsn, $postgresUser, $postgresPassword);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}