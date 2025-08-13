<?php
// auth/register.php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
    http_response_code(400);
    echo 'Dados inválidos';
    exit;
  }

  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
  $stmt->execute([$email]);
  if ($stmt->fetch()) {
    http_response_code(409);
    echo 'E-mail já cadastrado';
    exit;
  }

  $hash = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
  $stmt->execute([$name, $email, $hash]);

  $_SESSION['user_id'] = (int)$pdo->lastInsertId();
  $_SESSION['user_name'] = $name;
  header('Location: /public/app.php');
  exit;
}
