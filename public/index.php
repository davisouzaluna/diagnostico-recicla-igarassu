<?php
// public/index.php
require_once __DIR__ . '/../config/db.php';
if (isset($_SESSION['user_id'])) { header('Location: /public/app.php'); exit; }
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Igarassu Recicla – Colabore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/styles.css" rel="stylesheet">
</head>
<body class="bg-recicla">
<div class="container py-4">
  <div id="onboarding" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-inner rounded shadow">
      <div class="carousel-item active p-4 bg-dark text-white" style="min-height: 320px; background:url('./img/reciclagem.png') center/cover;">
        <div class="bg-overlay p-4">
          <h3>Você sabia?</h3>
          <p>O descarte incorreto de resíduos contribui para enchentes e doenças em nosso próprio bairro.</p>
          <span class="badge bg-warning text-dark">O Problema Local</span>
        </div>
      </div>
      <div class="carousel-item p-4 bg-success text-white" style="min-height: 320px; background:url('https://images.unsplash.com/photo-1616091093714-44d8d5ca04f8?q=80&w=1200') center/cover;">
        <div class="bg-overlay p-4">
          <h3>A oportunidade escondida</h3>
          <p>O que muitos veem como “lixo” é trabalho e renda para muitas famílias.</p>
          <span class="badge bg-light text-dark">Renda & Dignidade</span>
        </div>
      </div>
      <div class="carousel-item p-4 bg-primary text-white" style="min-height: 320px; background:url('https://images.unsplash.com/photo-1526243741027-444d633d7365?q=80&w=1200') center/cover;">
        <div class="bg-overlay p-4">
          <h3>Sua missão</h3>
          <p>Cada registro seu no mapa é um voto por uma Igarassu mais limpa.</p>
          <span class="badge bg-light text-dark">Ajude a mostrar onde a coleta é mais necessária</span>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#onboarding" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#onboarding" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Criar conta</h5>
          <form method="post" action="../auth/register.php">
            <div class="mb-2"><label class="form-label">Nome</label><input name="name" class="form-control" required></div>
            <div class="mb-2"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Senha (mín. 6)</label><input type="password" name="password" class="form-control" minlength="6" required></div>
            <button class="btn btn-primary w-100 btn-lg">Começar a colaborar</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Entrar</h5>
          <form method="post" action="/auth/login.php">
            <div class="mb-2"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" required></div>
            <button class="btn btn-outline-primary w-100 btn-lg">Entrar</button>
          </form>
          <!--<div class="text-muted small mt-3">Ou use login social no futuro.</div>-->
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
