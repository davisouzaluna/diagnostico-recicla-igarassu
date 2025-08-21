<template>
  <v-container class="d-flex flex-row justify-end">
    <v-btn 
    color="primary"
    prepend-icon="mdi-map-marker"
    @click="navigateTo('/location/create')"
    >
    Registrar disponibilidade
    </v-btn>
  </v-container>
  <Map/>
</template>

<script setup lang="ts">

</script>
