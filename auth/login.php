<?php
// auth/login.php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo 'Credenciais inválidas';
    exit;
  }

  $_SESSION['user_id'] = (int)$user['id'];
  $_SESSION['user_name'] = $user['name'];
  header('Location: /public/app.php');
  exit;
}
