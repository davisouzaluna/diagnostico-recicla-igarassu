<?php
// public/index.php
require_once __DIR__ . '/../config/db.php';
if (isset($_SESSION['user_id'])) { header('Location: /public/app.php'); exit; }
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>RenovaLoop - Colabore</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/public/assets/styles.css" rel="stylesheet">
  <link href="/public/assets/renovaloop.css" rel="stylesheet">

</head>
<body class="bg-recicla">

<body class="bg-recicla">

<!-- Navbar topo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container justify-content-center">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="/public/img/recicla.png" alt="Ícone RenovaLoop" width="30" height="30" 
           class="d-inline-block align-text-top me-2">
      <span class="fw-bold">RenovaLoop</span>
    </a>
  </div>
</nav>

<!-- Introdução / Onboarding -->
    <div id="onboarding" class="carousel slide mb-5" data-bs-ride="carousel">
      <div class="carousel-inner shadow">
        <div class="carousel-item active text-white" style="background: url('/public/img/leaf.jpg'); min-height: 360px;">
          <div class="overlay p-5">
            <h2 class="text-renovaloop">Revolucione seu jeito de reciclar</h2>
            <p>Cada ação faz o ciclo da sustentabilidade girar mais rápido para um planeta mais saudável.</p>
            <span class="badge bg-black text-renovaloop">Bem-vindo ao RenovaLoop</span>
          </div>
        </div>
        <div class="carousel-item" 
     style="background: url('/public/img/community-recycle.jpeg') center 30% / cover; min-height: 360px;">

          <div class="overlay p-5">
            <h2>Impacto que gera renda</h2>
            <p>Transforme resíduos em oportunidades de trabalho para inúmeras famílias.</p>
            <span class="badge bg-black text-renovaloop">Cooperação Local</span>
          </div>
        </div>
        <div class="carousel-item text-white" style="background: url('/public/img/map-pins.jpg') center/cover; min-height: 360px;">
          <div class="overlay p-5">
            <h2>Pontos de coleta na palma da mão</h2>
            <p>Registre e descubra locais de descarte corretamente mapeados em sua região.</p>
            <span class="badge bg-black text-renovaloop">Mapeamento Fácil</span>
          </div>
        </div>
      </div>

      <button class="carousel-control-prev btn-square" type="button" data-bs-target="#onboarding" data-bs-slide="prev">
  <span class="carousel-control-prev-icon"></span>
</button>

<button class="carousel-control-next btn-square" type="button" data-bs-target="#onboarding" data-bs-slide="next">
  <span class="carousel-control-next-icon"></span>
</button>

    </div>

  

  <div class="row g-4 align-items-stretch">
  <div class="col-md-6 d-flex">
    <div class="card shadow-sm flex-fill">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title text-center">Criar conta</h5>
        <form method="post" action="../auth/register.php" class="d-flex flex-column h-100">
          <div class="mb-2">
            <label class="form-label">Nome</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Senha (mín. 6)</label>
            <input type="password" name="password" class="form-control" minlength="6" required>
          </div>
          <button class="btn btn-primary w-100 btn-lg mt-auto">Começar a colaborar</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-6 d-flex">
    <div class="card shadow-sm flex-fill">
      <div class="card-body d-flex flex-column ">
        <h5 class="card-title text-center">Entrar</h5>
        <form method="post" action="/auth/login.php" class="d-flex flex-column h-100">
          <div class="mb-2">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-outline-primary w-100 btn-lg mt-auto">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



      <footer class="footer-verde mt-5 ">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h5>Contato</h5>
                        <p class="mb-1">E-mail: <a href="mailto:contato@renovaloop.org">contato@renovaloop.org</a></p>
                        <p>Telefone: (81) 9 9999-9999</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Links úteis</h5>
                        <ul class="list-unstyled">
                            <li><a class="nav-link" href="/public/sobre.php">Sobre o Projeto</a></li>

                            <li><a href="#">Como contribuir</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Redes sociais</h5>
                        <p><a href="#" class="text-white"><i class="bi bi-facebook icon-facebook"></i>Facebook</a></p>
                        <p><a href="#" class="text-white"><i class="bi bi-instagram icon-instagram"></i>Instagram</a></p>
                        <p><a href="#" class="text-white"><i class="bi bi-whatsapp icon-whatsapp"></i>WhatsApp</a></p>
                    </div>

                </div>
                <hr class="mt-4">
                <div class="text-center small text-white-50">© <?= date('Y') ?> Igarassu Recicla. Todos os direitos reservados.</div>
            </div>
        </footer>

</body>
</html>
