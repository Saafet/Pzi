<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "ID projekta nije poslan"]);
    exit;
}

$id = intval($data['id']);

$pdo = new PDO("mysql:host=localhost;dbname=pzi102025;charset=utf8", "pzi102025", "csdigital2025");

try {
    $stmt = $pdo->prepare("DELETE FROM projekti WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Projekt uspješno odjavljen."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Greška: " . $e->getMessage()]);
}
