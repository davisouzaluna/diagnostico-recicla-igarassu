<?php
// public/app.php
require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit;
}


// materiais para o select
$materials = $pdo->query('SELECT id, name FROM materials ORDER BY name')->fetchAll();
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Igarassu Recicla – App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="/public/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="/public/assets/styles.css" rel="stylesheet">
</head>

<body class="bg-recicla">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Igarassu Recicla</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" data-section="registrar" href="#">Registrar</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="mapa" href="#">Mapa</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="perfil" href="#">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="diagnostico" href="#">Diagnóstico</a></li>
                </ul>
                <span class="navbar-text me-3">Olá, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a class="btn btn-sm btn-outline-light" href="../auth/logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Registrar -->
        <section id="sec-registrar" class="section">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Registrar disponibilidade de material</h5>
                            <form id="formContribution">
                                <div class="mb-2">
                                    <label class="form-label">Tipo de material</label>
                                    <select class="form-select" name="material_id" required>
                                        <?php foreach ($materials as $m): ?>
                                            <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Quantidade (estimada)</label>
                                    <select class="form-select" name="quantity" required>
                                        <option>Saco Pequeno</option>
                                        <option>Saco Grande</option>
                                        <option>Caixa</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Localização por CEP</label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="cep"
                                            placeholder="CEP (ex.: 53610-000)"
                                            inputmode="numeric"
                                            autocomplete="postal-code"
                                            maxlength="9">
                                        <button type="button" id="btnCEP" class="btn btn-outline-secondary">Usar CEP</button>
                                    </div>
                                    <div id="cepStatus" class="small text-muted mt-1">Digite o CEP ou use sua localização.</div>
                                </div>

                                <div class="mb-2 d-flex gap-2">
                                    <button type="button" id="btnGeo" class="btn btn-outline-secondary">Usar minha localização</button>
                                    <div id="geoStatus" class="small text-muted align-self-center">Aguardando localização…</div>
                                </div>
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="lng" id="lng">
                                <button class="btn btn-primary w-100 mt-2">Enviar</button>
                            </form>
                            <div id="formFeedback" class="alert alert-success mt-3 d-none">Registro adicionado! Veja no mapa.</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="mapPreview" class="rounded shadow-sm" style="height: 360px;"></div>
                </div>
            </div>
        </section>

        <!-- Mapa -->
        <section id="sec-mapa" class="section d-none">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-2">Pontos registrados</h5>
                    <div id="mapFull" style="height: 480px;"></div>
                </div>
            </div>
        </section>

        <!-- Perfil -->
        <section id="sec-perfil" class="section d-none">
            <div class="row g-3">
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Suas conquistas</h5>
                            <div id="badges" class="d-flex gap-2 mt-2">
                                <!-- badges renderizadas via JS -->
                            </div>
                            <div class="small text-muted mt-2" id="contribCount"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Suas contribuições</h5>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Material</th>
                                            <th>Quantidade</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="myContribs"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Diagnóstico (Mapa de Calor) -->
        <section id="sec-diagnostico" class="section d-none">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-end gap-2 mb-3">
                        <div class="me-auto">
                            <h5 class="card-title mb-1">Mapa de calor de contribuições</h5>
                            <div class="small text-muted">Densidade de pontos registrados por material e período</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <div>
                                <label class="form-label small mb-1">Material</label>
                                <select id="heatMaterial" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <?php foreach ($materials as $m): ?>
                                        <option value="<?= (int)$m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label small mb-1">Período</label>
                                <select id="heatDays" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    <option value="30">Últimos 30 dias</option>
                                    <option value="90">Últimos 90 dias</option>
                                    <option value="365">Últimos 12 meses</option>
                                </select>
                            </div>
                            <button id="btnHeatReload" class="btn btn-sm btn-primary">Aplicar</button>
                        </div>
                    </div>

                    <div id="heatMap" class="rounded border" style="height: 520px;"></div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="small text-muted">Intensidade ponderada por quantidade (Pequeno=1, Caixa=2, Grande=3)</div>
                        <div id="heatMeta" class="small text-muted">0 pontos</div>
                    </div>
                </div>
            </div>
        </section>


        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>


        

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const USER_ID = <?= (int)$_SESSION['user_id'] ?>;
        </script>
        <script src="/public/assets/app.js"></script>
</body>

</html>