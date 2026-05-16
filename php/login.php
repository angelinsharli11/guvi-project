<?php

header("Content-Type: application/json");
require_once "config.php";

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if ($email == "" || $password == "") {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password required"
    ]);
    exit;
}

try {
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user["password"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password"
        ]);
        exit;
    }

    $token = bin2hex(random_bytes(32));

    $sessionData = json_encode([
        "user_id" => $user["id"],
        "name" => $user["name"],
        "email" => $user["email"]
    ]);

    $redis->setex("session:" . $token, 3600, $sessionData);

    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "token" => $token,
        "email" => $user["email"]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Login failed"
    ]);
}

?>