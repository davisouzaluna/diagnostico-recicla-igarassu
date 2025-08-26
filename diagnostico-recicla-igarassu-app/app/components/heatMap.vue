<template>
	<div class="heatmap-controls">
		<p>Quantidade de pontos registrados: {{ locations.length }}</p>
	</div>
	<div style="height: 80vh; width: 70vw">
		<LMap
			ref="map"
			use-global-leaflet
			:options="{
				zoomControl: false,
			}"
			@ready="onMapReady"
		>
			<LTileLayer
				url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
				attribution='&amp;copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
				layer-type="base"
				name="OpenStreetMap"
			/>
			<!-- Mantendo marcadores opcionais para referência -->
			<LMarker
				v-for="location in props.locations"
				:key="location.id"
				:lat-lng="[location.lat, location.lng]"
			>
				<LTooltip> Clique para mais informações </LTooltip>
				<LPopup>
					<p>Tipo de material: {{ location.material_info.type }}</p>
					<p>Quantidade: {{ location.material_info.size }}</p>
					<p>
						Endereço: {{ location.cep_info.rua }}, {{ location.cep_info.bairro }},
						{{ location.cep_info.numero }}, {{ location.cep_info.cep }}
					</p>
					<p>Contato: {{ location.cep_info.contato }}</p>
				</LPopup>
			</LMarker>
		</LMap>
	</div>
</template>

<script setup lang="ts">
import L, { type Map } from 'leaflet';
import 'leaflet.heat';
import { ref, watch, onMounted, onActivated } from 'vue';
import locationsService from '~/services/locationsService';
import type { Coordinate, LocationType } from '~/types/location';

// Definição de tipos para o heatmap
interface HeatLayerOptions {
	radius?: number;
	blur?: number;
	maxZoom?: number;
	max?: number;
	minOpacity?: number;
	gradient?: { [key: number]: string };
}

interface HeatLayer {
	addTo(map: Map): HeatLayer;
	setLatLngs(latlngs: [number, number, number][]): void;
	setOptions(options: HeatLayerOptions): void;
	remove(): void;
}
const getIntensityBySizeType = (sizeString: string): number => {
	if (!sizeString) return 0.4; // Valor padrão se estiver vazio

	const normalizedSize = sizeString.toLowerCase().trim();

	// Mapeamento específico para os valores do seu banco
	switch (normalizedSize) {
		case 'caixa':
			return 0.9; // Maior intensidade - VERMELHO
		case 'saco grande':
			return 0.7; // Intensidade média - AMARELO
		case 'saco pequeno':
			return 0.4; // Menor intensidade - AZUL
		default:
			// Para valores inesperados, usa uma intensidade média
			console.warn(`Tamanho não reconhecido: "${sizeString}". Usando intensidade padrão.`);
			return 0.5;
	}
};

const analyzeData = () => {
	// Contagem por tipo de tamanho
	const sizeCounts: { [key: string]: number } = {};
	locations.value.forEach((loc) => {
		const sizeType = loc.material_info?.size || 'não informado';
		sizeCounts[sizeType] = (sizeCounts[sizeType] || 0) + 1;
	});

	console.log('Distribuição por tipo de embalagem:');
	Object.entries(sizeCounts).forEach(([size, count]) => {
		const intensity = getIntensityBySizeType(size);
		const color = intensity >= 0.8 ? '🔴' : intensity >= 0.6 ? '🟡' : '🔵';
		console.log(`${color} "${size}": ${count} pontos → Intensidade: ${intensity}`);
	});
};

declare module 'leaflet' {
	function heatLayer(latlngs: [number, number, number][], options?: HeatLayerOptions): HeatLayer;
}

// Dados LOCAIS da API
const locations = ref<LocationType[]>([]);
const map = ref<Map | null>(null);
const showMarkers = ref(true); // Mostrar marcadores por padrão
const heatmapIntensity = ref(0.5);
const heatmapRadius = ref(25);
let heatmapLayer: HeatLayer | null = null;

// Remove props desnecessárias já que você busca dados da API
const props = defineProps({
	selectedPoint: {
		type: Object as PropType<Coordinate>,
		default: () => ({
			lat: 0,
			lng: 0,
		}),
	},
});

// Busca dados da API
async function getLocations() {
	try {
		const data = await locationsService.getLocations();
		locations.value = data; // Substitui o array ao invés de usar push
		updateHeatmap(); // Atualiza o heatmap após buscar dados
	} catch (e: any) {
		alert(e.message || 'Erro ao carregar localizações');
		console.error('Erro:', e);
	}
}

// Observa mudanças nos controles
watch([heatmapIntensity, heatmapRadius], () => {
	updateHeatmap();
});

// Observa mudanças nos dados LOCAIS
watch(
	locations,
	() => {
		updateHeatmap();
	},
	{ deep: true },
);

const onMapReady = async (leafletObject: Map): Promise<void> => {
	map.value = leafletObject;

	// Localização do usuário
	leafletObject.locate({
		setView: true,
		maxZoom: 16,
	});

	// Define vista inicial para Igarassu, PE
	leafletObject.setView([-7.83437, -34.9064], 13);

	leafletObject.on('locationfound', async (e) => {
		const radius = e.accuracy;
		L.marker(e.latlng).addTo(leafletObject).bindPopup(`Você está aqui!`).openPopup();
		L.circle(e.latlng, radius).addTo(leafletObject);
	});

	// Busca dados quando o mapa estiver pronto
	getLocations();
};

// Cria o mapa de calor com dados LOCAIS
const createHeatmap = () => {
	if (!map.value || locations.value.length === 0) {
		console.log('Mapa não pronto ou sem dados do banco');
		return;
	}

	const heatmapData = locations.value.map((location) => {
		const sizeString = location.material_info?.size || '';
		const intensity = getIntensityBySizeType(sizeString);

		return [location.lat, location.lng, intensity] as [number, number, number];
	});

	// Mostra análise dos dados do banco
	analyzeData();

	// Remove heatmap anterior se existir
	if (heatmapLayer) {
		heatmapLayer.remove();
		heatmapLayer = null;
	}

	try {
		heatmapLayer = L.heatLayer(heatmapData, {
			radius: heatmapRadius.value,
			blur: 15,
			maxZoom: 17,
			max: 1.0,
			minOpacity: 0.1,
			gradient: {
				0.3: 'blue', // Azul - Saco pequeno
				0.5: 'cyan', // Ciano - Valores intermediários
				0.6: 'lime', // Verde Lima - Transição
				0.7: 'yellow', // Amarelo - Saco grande
				0.9: 'red', // Vermelho - Caixa
			},
		}).addTo(map.value);

		console.log('✅ Heatmap criado com dados do banco');
	} catch (error) {
		console.error('Erro ao criar heatmap:', error);
	}
};

// Atualiza o heatmap
const updateHeatmap = () => {
	if (heatmapLayer && map.value && locations.value.length > 0) {
		const heatmapData = locations.value.map((location) => {
			const sizeString = location.material_info?.size || '';
			const intensity = getIntensityBySizeType(sizeString);

			return [location.lat, location.lng, intensity] as [number, number, number];
		});

		heatmapLayer.setLatLngs(heatmapData);
		heatmapLayer.setOptions({
			radius: heatmapRadius.value,
		});

		console.log('🔄 Heatmap atualizado');
	} else {
		createHeatmap();
	}
};

// Alternar marcadores
const toggleMarkers = () => {
	showMarkers.value = !showMarkers.value;
};

// Lifecycle hooks
onMounted(() => {
	// Se o mapa já estiver pronto, busca dados
	if (map.value) {
		getLocations();
	}
});

onActivated(() => {
	getLocations();
});
</script>

<style scoped lang="scss">
.heatmap-controls {
	z-index: 1000;
	background: white;
	padding: 15px;
	border-radius: 8px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
	display: flex;
	flex-direction: column;
	gap: 10px;
	min-width: 200px;
}

.control-btn {
	padding: 8px 12px;
	background: #007cba;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;
	font-size: 12px;

	&:hover {
		background: #005a87;
	}
}

.intensity-control,
.radius-control {
	display: flex;
	align-items: center;
	gap: 8px;
	font-size: 12px;

	label {
		font-weight: bold;
		min-width: 60px;
	}

	input[type='range'] {
		flex: 1;
	}

	span {
		min-width: 30px;
		text-align: center;
		font-weight: bold;
	}
}
</style>
