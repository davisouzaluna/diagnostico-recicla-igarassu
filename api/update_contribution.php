<?php
// api/update_contribution.php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401); echo json_encode(['error' => 'auth']); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);
$quantity = $data['quantity'] ?? null;

if (!$id || !in_array($quantity, ['Saco Pequeno','Saco Grande','Caixa'], true)) {
  http_response_code(400); echo json_encode(['error' => 'invalid']); exit;
}

$stmt = $pdo->prepare('UPDATE contributions SET quantity=?, updated_at=NOW() WHERE id=? AND user_id=? AND is_active=1');
$stmt->execute([$quantity, $id, $_SESSION['user_id']]);

echo json_encode(['ok' => true]);
