<template>
    <v-btn 
    prepend-icon="mdi-arrow-left"
    variant="text" 
    color="primary"
    @click="navigateTo('/dashboard')"
    >
    Voltar
    </v-btn>
    <v-container class="d-flex justify-center">
       <h1>Diagnóstico</h1>
    </v-container>
<HeatMap/>
</template>

<script setup lang="ts">
</script>