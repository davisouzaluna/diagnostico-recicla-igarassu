<?php
// api/delete_contribution.php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401); echo json_encode(['error' => 'auth']); exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);

if (!$id) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }

$stmt = $pdo->prepare('UPDATE contributions SET is_active=0, updated_at=NOW() WHERE id=? AND user_id=?');
$stmt->execute([$id, $_SESSION['user_id']]);

echo json_encode(['ok' => true]);
