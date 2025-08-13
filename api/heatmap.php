<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Não autorizado']);
  exit;
}

header('Content-Type: application/json; charset=utf-8');

// filtros opcionais
$materialId = isset($_GET['material_id']) && $_GET['material_id'] !== '' ? (int)$_GET['material_id'] : null;
$days = isset($_GET['days']) && $_GET['days'] !== '' ? (int)$_GET['days'] : null;

// monte condições dinamicamente
$conds = ['c.lat IS NOT NULL', 'c.lng IS NOT NULL'];
$params = [];

if ($materialId) {
  $conds[] = 'c.material_id = :material_id';
  $params[':material_id'] = $materialId;
}
if ($days) {
  $conds[] = "c.created_at >= (NOW() - INTERVAL {$days} DAY)";
}


// Ajuste o nome da tabela "contributions" se necessário
$sql = "
  SELECT
    CAST(c.lat AS DECIMAL(10,6))  AS lat,
    CAST(c.lng AS DECIMAL(10,6))  AS lng,
    -- converte a coluna quantity em peso (ajuste os rótulos conforme seu app)
    CASE
      WHEN c.quantity = 'Saco Pequeno' THEN 1.0
      WHEN c.quantity = 'Caixa' THEN 2.0
      WHEN c.quantity = 'Saco Grande' THEN 3.0
      ELSE 1.0
    END AS weight
  FROM contributions c
  " . (count($conds) ? 'WHERE ' . implode(' AND ', $conds) : '');

$stmt = $pdo->prepare($sql);

// bind seguro para :days (inteiro) e demais
foreach ($params as $k => $v) {
  if ($k === ':days') {
    $stmt->bindValue($k, $v, PDO::PARAM_INT);
  } else {
    $stmt->bindValue($k, $v);
  }
}

$stmt->execute();
$data = $stmt->fetchAll();

echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
