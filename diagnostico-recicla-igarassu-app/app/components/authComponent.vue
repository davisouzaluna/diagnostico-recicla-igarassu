<template>
  <!-- botões "ativadores" -->
  <v-row class="ga-0 d-flex flex-nowrap mr-8">
      <v-col cols="auto" >
      <v-btn variant="flat"  color="primary" @click="register = true">Cadastre-se</v-btn>
      </v-col>
      <v-col cols="auto">
      <v-btn variant="flat" color="primary" @click="login = true">Login</v-btn>
    </v-col>
    </v-row>

    <!-- modal de login -->
    <v-dialog 
    v-model="login"
     persistent="false"
    >
      <v-card color="background" class="w-33 ma-auto d-{w-75}">
        <v-btn
				icon="mdi-close"
				class="ml-auto"
				variant="text"
				@click="login = false"
			>
			</v-btn>
        <img
        class="ma-auto"
        src="~/assets/images/logo-diagnostico-recicla-igarassu.svg"
        alt="Ícone do diagnóstico recicla Igarassu"
        style="width:8rem;height:8rem"
        >
        <v-form>
        <h1 class="ml-auto mr-auto">Login</h1>
        <v-label  for="email">Email:</v-label>
        <v-text-field
         id="email"
         placeholder="Digite seu email"
         required
         ></v-text-field>

        <v-label for="password">Senha:</v-label>
        <v-text-field 
          id="password"
          placeholder="Digite sua senha"
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
				  :type="visible ? 'text' : 'password'"
					@click:append-inner="visible = !visible"
          required
          ></v-text-field>
         <v-btn>Fazer login</v-btn>
         <v-container>
					<NuxtLink 
          @click="() => {
								register = true;
								login = false;
							}"
          style="cursor: pointer;text-decoration: underline"
              >Ainda não tem conta? Cadastre-se</NuxtLink>
				  </v-container>
      </v-form>
      </v-card>
    </v-dialog>


    <!-- modal de cadastro -->
	<v-dialog
		v-model="register"
		max-width="600px"
	>
		<v-card class="pa-1" color="background">
			<v-btn
				icon="mdi-close"
				class="ml-auto"
				variant="text"
				@click="register = false"
			>
			</v-btn>
      <img
        class="ma-auto"
        src="~/assets/images/logo-diagnostico-recicla-igarassu.svg"
        alt="Ícone do diagnóstico recicla Igarassu"
        style="width:8rem;height:8rem"
        >
			<v-form>
				<h1 class="ml-auto mr-auto">Cadastro</h1>
				<v-window v-model="step">
					<!-- Etapa 1 -->
					<v-window-item :value="0">
						<v-label for="name">Digite seu nome:</v-label>
						<v-text-field
							id="name"
							variant="outlined"
							v-model="register.name"
							placeholder="Digite seu nome"
						/>

						<v-label for="email">Digite seu e-mail:</v-label>
						<v-text-field
							id="email"
							variant="outlined"
							type="email"
							v-model="register.email"
							placeholder="Digite seu e-mail"
						/>
					</v-window-item>

					<!-- Etapa 3 -->
					<v-window-item :value="1">
						<v-label for="password">Digite sua senha:</v-label>
						<v-text-field
							id="password"
							variant="outlined"
							v-model="register.password"
							placeholder="Digite sua senha"
							:append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
							:type="visible ? 'text' : 'password'"
							@click:append-inner="visible = !visible"
						/>

						<v-label for="confirmPassword">Confirme sua senha:</v-label>
						<v-text-field
							id="confirmPassword"
							variant="outlined"
							v-model="register.confirmPassword"
							placeholder="Confirme sua senha"
							:append-inner-icon="visibleConfirm ? 'mdi-eye-off' : 'mdi-eye'"
							:type="visibleConfirm ? 'text' : 'password'"
							@click:append-inner="visibleConfirm = !visibleConfirm"
						/>
					</v-window-item>
				</v-window>

				<!-- Botões de navegação -->
				<div class="d-flex justify-space-between mt-2">
					<v-btn
						text
						v-show="step > 0"
						@click="step--"
						prepend-icon="mdi-arrow-left"
					>
						Voltar
					</v-btn>

					<v-spacer />

					<v-btn
						v-if="step < 1"
						color="primary"
						@click="step++"
						append-icon="mdi-arrow-right"
					>
						Próximo
					</v-btn>

					<v-btn
						v-else
						color="primary"
						type="submit"
					>
						Cadastrar
					</v-btn>
				</div>
				<v-container>
					<a
						style="cursor: pointer; text-decoration: underline"
						@click="
							() => {
								register = false;
								login = true;
							}
						"
						>Já tem uma conta? Faça o login</a
					>
				</v-container>
			</v-form>
		</v-card>
	</v-dialog>
</template>

<script lang="ts" setup>
  const login = ref(false);
  const register = ref(false);
  const visible=ref(false);
  const step = ref(0);
</script>

<style scoped lang="scss">
img{
  width: 134px;
  height: 235px;
}
</style>