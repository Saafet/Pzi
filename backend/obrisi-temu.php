<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "ID teme nije poslan"]);
    exit;
}

$id = intval($data['id']);
$force = isset($data['force']) && $data['force'] === true;

$pdo = new PDO("mysql:host=localhost;dbname=pzi102025;charset=utf8", "pzi102025", "csdigital2025");

try {
    
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM projekti WHERE tema_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();

    if ($count > 0 && !$force) {
        
        echo json_encode([
            "success" => false,
            "message" => "Tema je zauzeta od strane učenika. Želite li je ipak obrisati?",
            "occupied" => true
        ]);
        exit;
    }

    if ($count > 0 && $force) {
        
        $stmt = $pdo->prepare("DELETE FROM projekti WHERE tema_id = ?");
        $stmt->execute([$id]);
    }

    
    $stmt = $pdo->prepare("DELETE FROM teme WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Tema uspješno obrisana."]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Greška: " . $e->getMessage()]);
}
