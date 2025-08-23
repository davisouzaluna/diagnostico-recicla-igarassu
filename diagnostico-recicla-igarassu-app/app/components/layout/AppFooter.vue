<template>
    <v-footer class="d-flex align-center justify-center ga-2 flex-wrap flex-grow-1 py-3 mt-16">
         <v-btn
      v-for="link in links"
      :key="link"
      :text="link"
      variant="text"
      rounded
    ></v-btn>

    <div class="flex-1-0-100 text-center mt-2">
      {{ new Date().getFullYear() }} — <strong>Diagnóstico Recicla Igarassu</strong>
    </div>
    </v-footer>
</template>

<script setup lang="ts">
    const links = [
    'Início',
    'Sobre nós',
    'Contate-nos',
  ]
</script>