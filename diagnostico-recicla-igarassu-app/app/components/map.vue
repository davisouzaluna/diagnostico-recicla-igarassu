<template>
  <div style="height:100vh; width:100vw">
    <LMap
      ref="map"
      use-global-leaflet
       @ready="onMapReady"
       :options="{
          zoomControl: false,
        }"
    >
      <LTileLayer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        attribution="&amp;copy; <a href=&quot;https://www.openstreetmap.org/&quot;>OpenStreetMap</a> contributors"
        layer-type="base"
        name="OpenStreetMap"
      />
    </LMap>
  </div>
</template>

<script setup lang="ts">
import L, { type Map } from 'leaflet';
import { ref } from 'vue'

const map = ref<Map | null>(null);

const onMapReady = async(leafletObject:Map): Promise<void> =>{
  map.value =  leafletObject;

   leafletObject.locate({
    setView: true,
    maxZoom: 16
  });

   leafletObject.on('locationfound', async (e) => {

    const radius = e.accuracy;

    L.marker(e.latlng)
        .addTo(leafletObject)
        .bindPopup(`Você está aqui!`)
        .openPopup();

    L.circle(e.latlng, radius)
        .addTo(leafletObject);

  });
}
</script>

<style scoped lang='scss'>
</style>