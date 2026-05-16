<?php

header("Content-Type: application/json");
require_once "config.php";

$token = $_POST["token"] ?? "";

if ($token == "") {
    echo json_encode([
        "status" => "error",
        "message" => "Token missing"
    ]);
    exit;
}

$redis->del("session:" . $token);

echo json_encode([
    "status" => "success",
    "message" => "Logged out successfully"
]);

?>