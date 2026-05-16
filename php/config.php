<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$mysqlHost = "localhost";
$mysqlPort = "3307";
$mysqlDb   = "guvi_project";
$mysqlUser = "root";
$mysqlPass = "";

try {
    $pdo = new PDO(
        "mysql:host=$mysqlHost;port=$mysqlPort;dbname=$mysqlDb",
        $mysqlUser,
        $mysqlPass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "MySQL connection failed"
    ]);
    exit;
}

try {
    $mongoClient = new MongoDB\Client($_ENV['MONGO_URI']);
    $mongoDb = $mongoClient->guvi_project;
    $profileCollection = $mongoDb->profiles;
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "MongoDB connection failed"
    ]);
    exit;
}

try {
    $redis = new Predis\Client([
        "scheme" => "tcp",
        "host" => "127.0.0.1",
        "port" => 6379
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Redis connection failed"
    ]);
    exit;
}
?>