<?php

header("Content-Type: application/json");
require_once "config.php";

$action = $_POST["action"] ?? "";
$token = $_POST["token"] ?? "";

if ($token == "") {
    echo json_encode([
        "status" => "unauthorized",
        "message" => "Token missing"
    ]);
    exit;
}

$session = $redis->get("session:" . $token);

if (!$session) {
    echo json_encode([
        "status" => "unauthorized",
        "message" => "Invalid session"
    ]);
    exit;
}

$user = json_decode($session, true);
$email = $user["email"];

if ($action == "get") {
    $profile = $profileCollection->findOne(["email" => $email]);

    if ($profile) {
        echo json_encode([
            "status" => "success",
            "profile" => [
                "age" => $profile["age"] ?? "",
                "dob" => $profile["dob"] ?? "",
                "contact" => $profile["contact"] ?? "",
                "address" => $profile["address"] ?? ""
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "profile" => null
        ]);
    }
    exit;
}

if ($action == "save") {
    $age = $_POST["age"] ?? "";
    $dob = $_POST["dob"] ?? "";
    $contact = $_POST["contact"] ?? "";
    $address = $_POST["address"] ?? "";

    $profileCollection->updateOne(
        ["email" => $email],
        [
            '$set' => [
                "email" => $email,
                "age" => $age,
                "dob" => $dob,
                "contact" => $contact,
                "address" => $address
            ]
        ],
        ["upsert" => true]
    );

    echo json_encode([
        "status" => "success",
        "message" => "Profile updated successfully"
    ]);
    exit;
}

echo json_encode([
    "status" => "error",
    "message" => "Invalid action"
]);

?>