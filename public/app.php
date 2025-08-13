<?php
// public/app.php
require_once __DIR__ . '/../config/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit;
}


// materiais para o select(versao inicial nao performatica- MVP)
$materials = $pdo->query('SELECT id, name FROM materials ORDER BY name')->fetchAll();
?>
<!doctype html>
<html lang="pt-br">


<head>
    <meta charset="utf-8">
    <title>RenovaLoop - Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="/public/manifest.json">
    <meta name="theme-color" content="#0d6efd">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="/public/assets/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/public/assets/styles.css" rel="stylesheet">

</head>

<body class="bg-recicla">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="/public/img/recicla.png" alt="Ícone RenovaLoop" width="30" height="30"
                    class="d-inline-block align-text-top me-2">
                <span class="fw-bold">RenovaLoop</span>
            </a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" data-section="registrar" href="#">Registrar</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="mapa" href="#">Mapa</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="perfil" href="#">Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="diagnostico" href="#">Diagnóstico</a></li>
                    <li class="nav-item"><a class="nav-link" data-section="sobre" href="#">Sobre o Projeto</a></li>

                </ul>
                <span class="navbar-text me-3">Olá, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a class="btn btn-sm btn-outline-light" href="../auth/logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Registrar -->
        <section id="sec-registrar" class="section">
            <div class="row g-3 align-items-stretch">
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
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
                    <div id="mapPreview" class="rounded shadow-sm h-100 w-100"></div>
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


        <!-- Sobre o Projeto -->
        <section id="sec-sobre" class="section d-none">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Sobre o Projeto</h4>
                    <p>
                        A gestão de resíduos sólidos urbanos no Brasil continua sendo um dos maiores desafios socioambientais da atualidade. Apesar da existência de marcos legais como a Política Nacional de Resíduos Sólidos (PNRS), instituída pela Lei nº 12.305/2010, muitos municípios ainda não implementaram ações efetivas de coleta seletiva, logística reversa ou inclusão das cooperativas de catadores.
                    </p>
                    <p>
                        Historicamente, os catadores de materiais recicláveis foram invisibilizados pelo poder público, mesmo desempenhando um papel estratégico na coleta seletiva. A legislação reconhece esses trabalhadores como agentes ambientais, mas a distância entre o texto legal e sua aplicação prática permanece significativa.
                    </p>
                    <p>
                        Estudos recentes, como o de Silva et al. (2023), apontam que a falta de integração entre os diversos atores — governo, cooperativas, cidadãos e empresas — e a ausência de planejamento baseado em dados contribuem para uma gestão ineficaz, com desperdício de recursos e perda de valor socioeconômico dos resíduos recicláveis.
                    </p>
                    <p>
                        Na Região Metropolitana do Recife (RMR), composta por 15 municípios, os problemas são ainda mais evidentes: coleta irregular, descarte inadequado, ausência de políticas de coleta seletiva e baixa articulação com cooperativas. Essa realidade compromete diretamente a saúde pública, o meio ambiente e a qualidade de vida da população.
                    </p>
                    <p>
                        O Decreto nº 7.404/2010, que regulamenta a PNRS, determina prioridade para a participação de cooperativas nos sistemas de coleta seletiva. No entanto, essa diretriz ainda é negligenciada em diversas localidades.
                    </p>
                    <p>
                        A Organização das Nações Unidas (ONU) destaca que a gestão sustentável de resíduos está diretamente ligada aos Objetivos de Desenvolvimento Sustentável (ODS), especialmente o ODS 11 (cidades sustentáveis) e o ODS 12 (consumo responsável). O Brasil, ao ratificar a Agenda 2030 e participar de conferências como a COP 26 e a futura COP 30, assumiu o compromisso de promover economias circulares e políticas de resíduos zero.
                    </p>
                    <p>
                        Este projeto nasce da urgência de repensar os modelos tradicionais de gestão de resíduos no Brasil. Propõe soluções baseadas em evidências e tecnologias acessíveis, com foco na valorização dos catadores, na articulação entre os atores da cadeia e na construção de cidades mais justas, limpas e sustentáveis.
                    </p>
                </div>
            </div>
        </section>





        <!-- Diagnóstico 
        <section id="sec-diagnostico" class="section d-none">
            <div class="card shadow-sm">
            <div class="card-body">
        <h5 class="card-title">Diagnóstico da cidade</h5>
        <div class="ratio ratio-16x9">
          <iframe src="https://app.powerbi.com/view?r=SEU_EMBED_URL_AQUI" allowfullscreen></iframe>
        </div>
        <div class="small text-muted mt-2">Mapa de calor, gráficos por tipo de material e filtros interativos.</div>
      </div>
    </div>
        </section>
    </div>
        -->

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const USER_ID = <?= (int)$_SESSION['user_id'] ?>;
        </script>
        <script src="/public/assets/app.js"></script>

        <footer class="footer-verde mt-5">
            <div class="container-fluid">
                <div class="row g-4">
                    <div class="col-md-4">
                        <h5>Contato</h5>
                        <p class="mb-1">E-mail: <a href="mailto:contato@renovaloop.org">contato@renovaloop.org</a></p>
                        <p>Telefone: (81) 9 9999-9999</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Links úteis</h5>
                        <ul class="list-unstyled">
                            <a class="nav-link" data-section="sobre" href="#">Sobre o Projeto</a>
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
                <div class="text-center small text-white-50">© <?= date('Y') ?> RenovaLoop. Todos os direitos reservados.</div>
            </div>
        </footer>
</body>

</html>