<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pzi102025;charset=utf8", "pzi102025", "csdigital2025");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, naziv FROM teme ORDER BY naziv ASC");
    $teme = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "teme" => $teme]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Greška kod dohvaćanja tema."]);
}
