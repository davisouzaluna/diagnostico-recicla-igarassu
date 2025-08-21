<template>
   <v-app>
      <v-app-bar
         scroll-behavior="elevate" 
         color="primary"
         class="d-flex justify-space-between"
         >
    <v-container style="cursor: pointer;" @click="navigateTo('/')">
      <img 
          src="~/assets/images/logo-horizontal-branca-diagnostico-recicla-igarassu.svg"
          alt="Logo do diagnóstico recicla Igarassu"
          >
    </v-container>
  </v-app-bar>
      <main>
      <section>
         <slot/>
      </section>
   </main>
   </v-app>
</template>

<style lang="scss">
img{
  width:9.375rem;
  height:2.813rem;
}
main {
  margin: 8rem auto 0 auto;
  width: 70vw;

  @media (max-width: 576px) {
    margin: 8rem auto 0 auto;
    width: 90vw;
}
}
</style>