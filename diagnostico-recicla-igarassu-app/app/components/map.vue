<template>
	<div style="height: 80vh; width: 100vw">
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
			<LMarker v-for="(location) in props.locations" :key="location.id" :lat-lng="[location.lat, location.lng]">
				<LTooltip>
					Clique para mais informações
				</LTooltip>
				<LPopup>
					<p>Tipo de material: {{ location.material_info.type }}</p>
					<p>Quantidade: {{ location.material_info.size }}</p>
					<p>Endereço: {{ location.cep_info.rua }}, {{ location.cep_info.bairro }}, {{ location.cep_info.numero }}, {{ location.cep_info.cep }} </p>
					<p>Contato: {{ location.cep_info.contato }}</p>
				</LPopup>
			</LMarker>
		</LMap>
	</div>
</template>

<script setup lang="ts">
import L, { type Map } from 'leaflet';
import { ref, type PropType } from 'vue';
import type { Coordinate, LocationType } from '~/types/location';

const props = defineProps({
	selectedPoint:{
		type: Object as PropType<Coordinate>,
		default:()=>({
			lat: 0,
			lng: 0 
		})
	},
	locations:{
		type: Array as PropType<LocationType[]>,
		default: ()=>([])
	}
});
watch(props, ({ selectedPoint: $new, locations })=>{
	if($new){
		addPoint($new)
	}
	console.log(locations)
},{
	deep: true
})
const map = ref<Map | null>(null);

const onMapReady = async (leafletObject: Map): Promise<void> => {
	map.value = leafletObject;

	leafletObject.locate({
		setView: true,
		maxZoom: 16,
	});
	leafletObject.setView([-7.83437, -34.9064], 13);
	leafletObject.on('locationfound', async (e) => {
		const radius = e.accuracy;

		L.marker(e.latlng).addTo(leafletObject).bindPopup(`Você está aqui!`).openPopup();

		L.circle(e.latlng, radius).addTo(leafletObject);
	});
};
let removePoint:L.Marker | null = null;

async function addPoint(point:Coordinate){
	if(removePoint){
		removePoint.remove()
	}
	if(map.value){
		removePoint = L.marker(point)
		.addTo(map.value)
		.bindPopup("Ponto selecionado")
		.openPopup()
	}
  } 
</script>

<style scoped lang="scss"></style>
