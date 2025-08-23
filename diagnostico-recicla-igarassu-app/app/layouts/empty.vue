<template>
  <v-app>
  <v-app-bar
    scroll-behavior="elevate"
    color="secondary"
    class="d-flex justify-space-between"
    >
    <v-container style="cursor: pointer;" @click="navigateTo('/')">
      <img 
          src="~/assets/images/logo-horizontal-colorida-diagnostico-recicla-igarassu.svg"
          alt="Logo do diagnóstico recicla Igarassu"
          >
    </v-container>
    <v-container>
      <authComponent/>
    </v-container>
  </v-app-bar>
  <slot />
  <LayoutAppFooter/>
  </v-app>
</template>
<style lang="scss">
html,body{
   height: 100vh;
  margin: 0;
}
img{
  width:9.375rem;
  height:2.813rem;
}
main {
  width: 100vw;
  overflow-y: auto;

  @media (max-width: 576px) {
    margin: 8rem auto 0 auto;
    width: 90vw;
}
}
</style>