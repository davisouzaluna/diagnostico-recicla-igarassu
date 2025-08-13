<?php
declare(strict_types=1);
session_start();

$dsn = 'mysql:host=localhost;dbname=igarassu_recicla;charset=utf8mb4';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    exit('Erro ao conectar ao banco: ' . $e->getMessage());
}

// Cria usuário de teste
$pdo->exec("DELETE FROM users WHERE email = 'teste@igarassu.local'");
$pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)")->execute([
    'Usuário Teste', 'teste@igarassu.local', password_hash('123456', PASSWORD_DEFAULT)
]);
$userId = (int)$pdo->lastInsertId();

// Busca materiais
$materials = $pdo->query("SELECT id FROM materials")->fetchAll(PDO::FETCH_COLUMN);
$quantities = ['Saco Pequeno', 'Saco Grande', 'Caixa'];

// Bairros simulados com coordenadas aproximadas
$bairros = [
  'Centro' => [-7.8280, -34.9060],
  'Nova Cruz' => [-7.8200, -34.9000],
  'Sítio Histórico' => [-7.8320, -34.9100],
  'Engenho Siqueira' => [-7.8350, -34.9150],
  'Monjope' => [-7.8400, -34.9200],
  'Agamenon Magalhães' => [-7.8250, -34.9050],
  'Jardim Igarassu' => [-7.8180, -34.8990],
  'Alto do Céu' => [-7.8300, -34.9080],
  'Vila Esperança' => [-7.8220, -34.9020],
  'Santa Rita' => [-7.8360, -34.9120]
];

// Função para gerar coordenadas próximas
function randomCoord(float $base, float $spread = 0.005): float {
    return $base + (mt_rand(-1000, 1000) / 100) * $spread;
}

// Prepara inserção
$stmt = $pdo->prepare("
    INSERT INTO contributions (user_id, material_id, quantity, lat, lng, created_at)
    VALUES (:user_id, :material_id, :quantity, :lat, :lng, :created_at)
");

$total = 0;
foreach ($bairros as $nome => [$baseLat, $baseLng]) {
    for ($i = 0; $i < 1000; $i++) {
        $materialId = $materials[array_rand($materials)];
        $quantity = $quantities[array_rand($quantities)];
        $lat = randomCoord($baseLat);
        $lng = randomCoord($baseLng);
        $daysAgo = mt_rand(0, 180);
        $createdAt = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days"));

        $stmt->execute([
            ':user_id' => $userId,
            ':material_id' => $materialId,
            ':quantity' => $quantity,
            ':lat' => $lat,
            ':lng' => $lng,
            ':created_at' => $createdAt
        ]);
        $total++;
    }
}

echo "✅ {$total} contribuições geradas em 10 bairros de Igarassu.";
