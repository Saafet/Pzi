<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['naziv']) || empty(trim($data['naziv']))) {
    echo json_encode(["success" => false, "message" => "Naziv teme je obavezan."]);
    exit;
}

$naziv = trim($data['naziv']);

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pzi102025;charset=utf8", "pzi102025", "csdigital2025");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM teme WHERE naziv = ?");
    $stmt->execute([$naziv]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["success" => false, "message" => "Tema s tim nazivom već postoji."]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO teme (naziv) VALUES (?)");
    $stmt->execute([$naziv]);

    echo json_encode(["success" => true, "message" => "Tema dodana."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Greška kod dodavanja teme."]);
}
