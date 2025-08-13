<?php
// api/create_contribution.php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401); echo json_encode(['error' => 'auth']); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$material_id = (int)($data['material_id'] ?? 0);
$quantity = $data['quantity'] ?? '';
$lat = (float)($data['lat'] ?? 0);
$lng = (float)($data['lng'] ?? 0);

if (!$material_id || !in_array($quantity, ['Saco Pequeno','Saco Grande','Caixa'], true) || !$lat || !$lng) {
  http_response_code(400); echo json_encode(['error' => 'invalid']); exit;
}

$stmt = $pdo->prepare('INSERT INTO contributions (user_id, material_id, quantity, lat, lng) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$_SESSION['user_id'], $material_id, $quantity, $lat, $lng]);

echo json_encode(['ok' => true, 'id' => (int)$pdo->lastInsertId()]);
