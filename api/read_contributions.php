<?php
// api/read_contributions.php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$userOnly = isset($_GET['mine']) && $_GET['mine'] === '1' && isset($_SESSION['user_id']);

if ($userOnly) {
  $stmt = $pdo->prepare(
    'SELECT c.id, c.material_id, m.name AS material, c.quantity, c.lat, c.lng, c.created_at
     FROM contributions c JOIN materials m ON m.id=c.material_id
     WHERE c.is_active=1 AND c.user_id=? ORDER BY c.created_at DESC'
  );
  $stmt->execute([$_SESSION['user_id']]);
} else {
  $stmt = $pdo->query(
    'SELECT c.id, c.material_id, m.name AS material, c.quantity, c.lat, c.lng, c.created_at
     FROM contributions c JOIN materials m ON m.id=c.material_id
     WHERE c.is_active=1 ORDER BY c.created_at DESC'
  );
}
echo json_encode($stmt->fetchAll());
