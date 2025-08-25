<template>
	<!-- botões "ativadores" -->
	<v-row class="ga-0 d-flex flex-nowrap justify-end">
		<v-col cols="auto">
			<v-btn
				variant="flat"
				color="primary"
				@click="dialogState = true"
				>Cadastre-se</v-btn
			>
		</v-col>
		<v-col cols="auto">
			<v-btn
				variant="flat"
				color="primary"
				@click="login = true"
				>Login</v-btn
			>
		</v-col>
	</v-row>

	<!-- modal de login -->
	<v-dialog v-model="login">
		<v-card
			color="secondary"
			class="w-33 ma-auto d-{w-75}"
		>
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
				style="width: 8rem; height: 8rem"
			/>
			<v-form @submit.prevent="submitLogin()">
				<h1 class="ml-auto mr-auto">Login</h1>
				<v-label for="email">Email:</v-label>
				<v-text-field
					id="email"
					placeholder="Digite seu email"
					v-model="login_data.email"
					required
				></v-text-field>

				<v-label for="password">Senha:</v-label>
				<v-text-field
					id="password"
					placeholder="Digite sua senha"
					:append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
					:type="visible ? 'text' : 'password'"
					@click:append-inner="visible = !visible"
					v-model="login_data.password"
					required
				></v-text-field>
				<v-btn
					type="submit"
					color="primary"
					>Fazer login</v-btn
				>
				<v-container>
					<NuxtLink
						@click="
							() => {
								dialogState = true;
								login = false;
							}
						"
						style="cursor: pointer; text-decoration: underline"
						>Ainda não tem conta? Cadastre-se</NuxtLink
					>
				</v-container>
			</v-form>
		</v-card>
	</v-dialog>

	<!-- modal de cadastro -->
	<v-dialog
		v-model="dialogState"
		max-width="600px"
	>
		<v-card
			class="pa-1"
			color="secondary"
		>
			<v-btn
				icon="mdi-close"
				class="ml-auto"
				variant="text"
				@click="dialogState = false"
			>
			</v-btn>
			<img
				class="ma-auto"
				src="~/assets/images/logo-diagnostico-recicla-igarassu.svg"
				alt="Ícone do diagnóstico recicla Igarassu"
				style="width: 8rem; height: 8rem"
			/>
			<v-form @submit.prevent="submitRegister()">
				<h1 class="ml-auto mr-auto">Cadastro</h1>
				<v-window v-model="step">
					<!-- Etapa 1 -->
					<v-window-item :value="0">
						<v-label for="name">Digite seu nome:</v-label>
						<v-text-field
							id="name"
							variant="outlined"
							v-model="register_data.name"
							placeholder="Digite seu nome"
						/>

						<v-label for="email">Digite seu e-mail:</v-label>
						<v-text-field
							id="email"
							variant="outlined"
							type="email"
							v-model="register_data.email"
							placeholder="Digite seu e-mail"
						/>
					</v-window-item>

					<!-- Etapa 3 -->
					<v-window-item :value="1">
						<v-label for="password">Digite sua senha:</v-label>
						<v-text-field
							id="password"
							variant="outlined"
							v-model="register_data.password"
							placeholder="Digite sua senha"
							:append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
							:type="visible ? 'text' : 'password'"
							@click:append-inner="visible = !visible"
						/>

						<v-label for="confirmPassword">Confirme sua senha:</v-label>
						<v-text-field
							id="confirmPassword"
							variant="outlined"
							v-model="register_data.confirmPassword"
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
						color="primary"
						variant="outlined"
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
								dialogState = false;
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
import authService from '~/services/authService';
import { dialogState } from '@/composables/useDialog.js'

const login = ref(false);
const register = ref(false);
const visible = ref(false);
const visibleConfirm = ref(false);
const step = ref(0);

const login_data = ref({
	email: '',
	password: '',
});

const register_data = ref({
	email: "",
	name: "",
	password: "",
	confirmPassword: ""
})

async function submitRegister(){
	try{
		await authService.register(register_data.value.name, register_data.value.email, register_data.value.password)
		navigateTo('/dashboard')
	}
	catch(e){
		console.log(e)
	}
}

async function submitLogin() {
	try {
		await authService.login(login_data.value.email, login_data.value.password);
		navigateTo('/dashboard');
	} catch (e) {
		alert(e!.msg);
		console.log(e);
	}
}
</script>

<style scoped lang="scss">
img {
	width: 134px;
	height: 235px;
}
</style>
