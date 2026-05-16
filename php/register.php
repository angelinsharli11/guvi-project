<?php

header("Content-Type: application/json");
require_once "config.php";

$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($name == "" || $email == "" || $password == "") {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

try {
    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$email]);

    if ($checkStmt->rowCount() > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Email already registered"
        ]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode([
        "status" => "success",
        "message" => "Registration successful"
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Registration failed"
    ]);
}

?>