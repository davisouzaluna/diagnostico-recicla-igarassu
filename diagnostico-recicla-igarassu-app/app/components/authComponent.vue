<template>
  <v-row class="ga-0 ma-8">
      <v-col cols="8" >
      <v-btn variant="flat"  color="primary">Cadastre-se</v-btn>
      </v-col>
      <v-col cols="4">
      <v-btn variant="flat" color="primary" @click="dialog = true">Login</v-btn>
    </v-col>
    </v-row>

    <v-dialog v-model="dialog">
      <v-card color="background" class="w-33 ma-auto d-{w-75}">
        <img
        class="ma-auto"
        src="~/assets/images/logo-vertical-colorida-diagnostico-recicla-igarassu.svg"
        alt="Logo vertical do diagnóstico recicla Igarassu"
        >
         <v-form class="pa-8">
        <v-label  for="email">Email:</v-label>
        <v-text-field
         id="email"
         placeholder="Digite seu email"
         ></v-text-field>

        <v-label for="password">Senha:</v-label>
        <v-text-field 
          id="password"
          placeholder="Digite sua senha"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
				  :type="visible ? 'text' : 'password'"
					@click:append-inner="visible = !visible"
         ></v-text-field>
         <v-btn>Fazer login</v-btn>
         <v-container>
					<NuxtLink>Ainda não tem conta? Cadastre-se</NuxtLink>
				  </v-container>
      </v-form>
      </v-card>
    </v-dialog>
</template>

<script lang="ts" setup>
  const dialog = ref(false);
  const visible=ref(false);
</script>

<style scoped lang="scss">
img{
  width: 134px;
  height: 235px;
}
</style>