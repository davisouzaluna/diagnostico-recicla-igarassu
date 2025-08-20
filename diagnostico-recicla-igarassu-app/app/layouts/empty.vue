<template>
  <v-app>
  <v-app-bar
    scroll-behavior="elevate"
    color="background"
    class="d-flex justify-space-between"
    >
    <v-container style="cursor: pointer;" @click="navigateTo('/')">
      <img 
          src="~/assets/images/logo-horizontal-colorida-diagnostico-recicla-igarassu.svg"
          alt="Logo do diagnóstico recicla Igarassu"
          >
    </v-container>
    <authComponent/>
  </v-app-bar>
  <slot />
  </v-app>
</template>
<style lang="scss">
img{
  width:9.375rem;
  height:2.813rem;
}
main {
  width: 100vw;

  @media (max-width: 576px) {
    margin: 8rem auto 0 auto;
    width: 90vw;
}
}
</style>