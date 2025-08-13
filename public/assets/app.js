// Leaflet já deve estar carregado via <script> no HTML:
// <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
// <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

let mapPreview, mapFull, previewMarker;
let fullMarkers = [];




function initMaps() {
  mapPreview = L.map('mapPreview').setView([-7.828, -34.906], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapPreview);

  mapFull = L.map('mapFull').setView([-7.828, -34.906], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(mapFull);
  
  mapFull.on('click', async () => {
  await resetMapRegion();
  });
  window.mapPreviewLeaflet = mapPreview;
  window.setPoint = (lat, lng, pan = true) => {
    if (!previewMarker) {
      previewMarker = L.marker([lat, lng], { draggable: true }).addTo(mapPreview);
      previewMarker.on('dragend', () => {
        const pos = previewMarker.getLatLng();
        document.getElementById('lat').value = pos.lat.toFixed(6);
        document.getElementById('lng').value = pos.lng.toFixed(6);
      });
    } else {
      previewMarker.setLatLng([lat, lng]);
    }
    document.getElementById('lat').value = Number(lat).toFixed(6);
    document.getElementById('lng').value = Number(lng).toFixed(6);
    if (pan) mapPreview.setView([lat, lng], Math.max(mapPreview.getZoom(), 15));
  };
}

function renderContributions(data) {
  data.forEach(item => {
    const marker = L.marker([item.lat, item.lng])
      .addTo(mapFull)
      .bindPopup(`<strong>${item.material}</strong><br>${item.quantity}`);
    markers.push(marker);
  })
}

function setSection(name) {
  document.querySelectorAll('.nav-link').forEach(a => a.classList.toggle('active', a.dataset.section === name));
  document.querySelectorAll('.section').forEach(s => s.classList.add('d-none'));
  document.getElementById('sec-'+name).classList.remove('d-none');
  if (name === 'mapa') mapFull.invalidateSize();
  if (name === 'diagnostico') setTimeout(initHeatMap, 0);
}
window.setSection = setSection;

async function fetchAllContribs() {
  const res = await fetch('/api/read_contributions.php');
  return await res.json();
}

async function fetchMyContribs() {
  const res = await fetch('/api/read_contributions.php?mine=1');
  return await res.json();
}

function renderFullMap(contribs) {
  fullMarkers.forEach(m => mapFull.removeLayer(m));
  fullMarkers = [];
  contribs.forEach(c => {
    const marker = L.marker([c.lat, c.lng]).addTo(mapFull)
      .bindPopup(`<strong>${c.material}</strong><br>${c.quantity}<br><small>${new Date(c.created_at).toLocaleString('pt-BR')}</small>`);
    fullMarkers.push(marker);
  });
}

function renderMyTable(contribs) {
  const tbody = document.getElementById('myContribs');
  tbody.innerHTML = '';
  contribs.forEach(c => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${new Date(c.created_at).toLocaleString('pt-BR')}</td>
      <td>${c.material}</td>
      <td>
        <select class="form-select form-select-sm qty" data-id="${c.id}">
          <option ${c.quantity==='Saco Pequeno'?'selected':''}>Saco Pequeno</option>
          <option ${c.quantity==='Saco Grande'?'selected':''}>Saco Grande</option>
          <option ${c.quantity==='Caixa'?'selected':''}>Caixa</option>
        </select>
      </td>
      <td>
        <button class="btn btn-sm btn-outline-danger del" data-id="${c.id}">Excluir</button>
      </td>
    `;
    tbody.appendChild(tr);
  });

  tbody.querySelectorAll('.qty').forEach(sel => {
    sel.addEventListener('change', async (e) => {
      const id = e.target.dataset.id;
      const quantity = e.target.value;
      await fetch('/api/update_contribution.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({id, quantity})
      });
      refreshAll();
    });
  });

  tbody.querySelectorAll('.del').forEach(btn => {
    btn.addEventListener('click', async () => {
      if (!confirm('Excluir registro?')) return;
      const id = btn.dataset.id;
      await fetch('/api/delete_contribution.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({id})
      });
      refreshAll();
    });
  });
}

function renderBadges(count) {
  const wrap = document.getElementById('badges');
  const countEl = document.getElementById('contribCount');
  wrap.innerHTML = '';
  const tiers = [
    {min:1,  name:'Bronze', color:'#cd7f32'},
    {min:5,  name:'Prata',  color:'#C0C0C0'},
    {min:10, name:'Ouro',   color:'#FFD700'}
  ];
  tiers.forEach(t => {
    const owned = count >= t.min;
    const b = document.createElement('div');
    b.className = 'badge-circle';
    b.style.borderColor = t.color;
    b.style.opacity = owned ? '1' : '.25';
    b.title = t.name;
    b.innerText = t.name.charAt(0);
    wrap.appendChild(b);
  });
  countEl.textContent = `Total de contribuições: ${count}`;
}

async function refreshAll() {
  const all = await fetchAllContribs();
  renderFullMap(all);
  const mine = await fetchMyContribs();
  renderMyTable(mine);
  renderBadges(mine.length);
}

document.addEventListener('DOMContentLoaded', () => {
  initMaps();

  document.querySelectorAll('.nav-link').forEach(a => {
    a.addEventListener('click', (e) => {
      e.preventDefault();
      setSection(a.dataset.section);
    });
  });

  const btnGeo = document.getElementById('btnGeo');
  const geoStatus = document.getElementById('geoStatus');
  btnGeo.addEventListener('click', () => {
    geoStatus.textContent = 'Obtendo localização…';
    navigator.geolocation.getCurrentPosition((pos) => {
      const { latitude, longitude } = pos.coords;
      geoStatus.textContent = `Localização definida.`;
      setPoint(latitude, longitude, true);
    }, () => {
      geoStatus.textContent = 'Não foi possível obter a localização.';
    }, { enableHighAccuracy: true, timeout: 8000 });
  });

  const form = document.getElementById('formContribution');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const payload = Object.fromEntries(fd.entries());
    payload.material_id = parseInt(payload.material_id, 10);
    payload.lat = parseFloat(payload.lat);
    payload.lng = parseFloat(payload.lng);

    if (!payload.lat || !payload.lng) {
      alert('Por favor, use sua localização antes de enviar.');
      return;
    }

    const res = await fetch('/api/create_contribution.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(payload)
    });

    if (res.ok) {
      document.getElementById('formFeedback').classList.remove('d-none');
      form.reset();
      refreshAll();
      setSection('mapa');
    } else {
      alert('Erro ao salvar. Tente novamente.');
    }
  });

  refreshAll();
});

// 🔥 Heatmap (Diagnóstico)
let heatMap, heatLayer, currentData = [];
const elMap = document.getElementById('heatMap');
const elMaterial = document.getElementById('heatMaterial');
const elDays = document.getElementById('heatDays');
const elMeta = document.getElementById('heatMeta');
const btnReload = document.getElementById('btnHeatReload');

function initHeatMap() {
  if (heatMap) return;
  heatMap = L.map('heatMap').setView([-7.834, -34.907], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap',
    maxZoom: 19
  }).addTo(heatMap);

    heatLayer = L.heatLayer([], {
    radius: 22,
    blur: 16,
    max: 1.0,
    gradient: {
      0.0: '#d2e3ff',
      0.4: '#86b7fe',
      0.7: '#0d6efd',
      1.0: '#0a58ca'
    }
  }).addTo(heatMap);

  loadHeatData();
}

async function loadHeatData() {
  if (!elMap) return;
  const qs = new URLSearchParams();
  if (elMaterial && elMaterial.value) qs.set('material_id', elMaterial.value);
  if (elDays && elDays.value) qs.set('days', elDays.value);

  const url = '../../api/heatmap.php' + (qs.toString() ? '?' + qs.toString() : '');
  elMap.classList.add('placeholder-glow');

  try {
    const res = await fetch(url, { credentials: 'same-origin' });
    const data = await res.json();
    currentData = Array.isArray(data) ? data : [];

    const pts = currentData
      .filter(d => d.lat && d.lng)
      .map(d => [parseFloat(d.lat), parseFloat(d.lng), Math.max(0.1, parseFloat(d.weight || 1))]);

    const maxW = pts.reduce((m, p) => Math.max(m, p[2]), 0.1);
    const norm = pts.map(p => [p[0], p[1], p[2] / maxW]);

    heatLayer.setLatLngs(norm);
    elMeta.textContent = `${pts.length} ponto${pts.length === 1 ? '' : 's'}`;

    if (pts.length > 1) {
      const bounds = L.latLngBounds(pts.map(p => [p[0], p[1]]));
      heatMap.fitBounds(bounds.pad(0.2));
    }
  } catch (e) {
    console.error('Erro ao carregar heatmap:', e);
    elMeta.textContent = 'Falha ao carregar';
  } finally {
    elMap.classList.remove('placeholder-glow');
  }
}

if (btnReload) btnReload.addEventListener('click', loadHeatData);

// 📍 CEP e geocodificação
const elCEP = document.getElementById('cep');
const elBtnCEP = document.getElementById('btnCEP');
const elCEPStatus = document.getElementById('cepStatus');
const elLat = document.getElementById('lat');
const elLng = document.getElementById('lng');

function formatCEP(value) {
  const digits = (value || '').replace(/\D/g, '').slice(0, 8);
  if (digits.length <= 5) return digits;
  return digits.slice(0, 5) + '-' + digits.slice(5);
}

if (elCEP) {
  elCEP.addEventListener('input', () => {
    elCEP.value = formatCEP(elCEP.value);
  });
}

async function viaCEP(cepDigits) {
  const res = await fetch(`https://viacep.com.br/ws/${cepDigits}/json/`);
  const data = await res.json();
  if (data.erro) throw new Error('CEP não encontrado');
  return data;
}

async function geocodeOSM(query) {
  const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&countrycodes=br&limit=1&q=${encodeURIComponent(query)}`;
  const res = await fetch(url, {
    headers: { 'Accept-Language': 'pt-BR' }
  });
  const data = await res.json();
  if (!Array.isArray(data) || data.length === 0) throw new Error('Endereço não localizado');
  const { lat, lon } = data[0];
  return { lat: parseFloat(lat), lng: parseFloat(lon) };
}

async function handleCEP() {
  try {
    const digits = (elCEP.value || '').replace(/\D/g, '');
    if (digits.length !== 8) {
      elCEPStatus.textContent = 'CEP inválido. Use 8 dígitos (ex.: 53610000 ou 53610-000).';
      elCEPStatus.className = 'small text-danger mt-1';
      return;
    }
    elCEPStatus.textContent = 'Buscando endereço...';
    elCEPStatus.className = 'small text-muted mt-1';

    const addr = await viaCEP(digits);
    const q1 = [addr.logradouro, addr.bairro, `${addr.localidade} - ${addr.uf}`, 'Brasil']
      .filter(Boolean).join(', ');

    let coords;
    try {
      coords = await geocodeOSM(q1);
    } catch {
      const q2 = [`${digits}`, `${addr.localidade} - ${addr.uf}`, 'Brasil'].join(', ');
      coords = await geocodeOSM(q2);
    }

    setPoint(coords.lat, coords.lng, true);
    elCEPStatus.textContent = `Localizado: ${addr.localidade} - ${addr.uf}`;
    elCEPStatus.className = 'small text-success mt-1';
  } catch (e) {
    console.error(e);
    elCEPStatus.textContent = 'Não foi possível localizar o CEP informado.';
    elCEPStatus.className = 'small text-danger mt-1';
  }
}

if (elBtnCEP) elBtnCEP.addEventListener('click', handleCEP);

if (elLat.value && elLng.value) {
  setPoint(parseFloat(elLat.value), parseFloat(elLng.value), true);
}


