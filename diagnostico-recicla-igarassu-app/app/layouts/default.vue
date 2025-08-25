<template>
	<v-app>
		<v-app-bar
			scroll-behavior="elevate"
			color="primary"
			class="d-flex justify-space-between"
		>
			<v-app-bar-nav-icon
				variant="text"
				@click="drawer = !drawer"
			></v-app-bar-nav-icon>
			<v-container
				style="cursor: pointer"
				@click="navigateTo('/dashboard')"
			>
				<img
					src="~/assets/images/logo-horizontal-branca-diagnostico-recicla-igarassu.svg"
					alt="Logo do diagnóstico recicla Igarassu"
				/>
			</v-container>
			<!-- Container do usuário -->
		<v-container class="text-center mr-8 d-flex justify-end">
			<v-menu
				:location="location"
				v-model="isOpen"
				class="ml-auto mr-0"
			>
				<template v-slot:activator="{ props }">
					<v-btn
						color="surface"
						variant="outlined"
						prepend-icon="mdi-account"
						:append-icon="!isOpen ? 'mdi-chevron-down' : 'mdi-chevron-up'"
						v-bind="props"
					>
						{{ $userStore.user.name }}
					</v-btn>
				</template>

				<v-list>
					<v-list-item
						v-for="(item, index) in items"
						:key="index"
						:value="index"
						:append-icon="item.icon"
						@click="item.action"
					>
						<v-list-item-title class="ma-0 pa-0">{{ item.title }}</v-list-item-title>
					</v-list-item>
				</v-list>
			</v-menu>
		</v-container>
		</v-app-bar>
		<v-navigation-drawer
			color="primary"
			v-model="drawer"
			:location="$vuetify.display.mobile ? 'bottom' : undefined"
			temporary
		>
				<v-list class="menu-height bg-primary">
					<v-list-item
						prepend-icon="mdi-home"
						title="Home"
						@click="navigateTo('/dashboard')"
						class="bg-primary"
					>
					</v-list-item>
					<v-list-item
						prepend-icon="mdi-chart-arc"
						title="Diagnóstico"
						@click="navigateTo('/diagnostic')"
						class="bg-primary"
					>
					</v-list-item>
					<v-list-item
						prepend-icon="mdi-information-outline"
						title="Sobre Nós"
						@click="navigateTo('/about')"
						class="bg-primary"
					>
					</v-list-item>
				</v-list>
		</v-navigation-drawer>
		<main>
			<section>
				<slot />
			</section>
		</main>
		<LayoutAppFooter
			color="primary"
		/>
	</v-app>
</template>

<script setup lang="ts">
import authService from '~/services/authService';

const { $userStore } = useNuxtApp();
const drawer = ref(false);
const isOpen = ref(false);

const location = ref('bottom center');


const items = [{ title: 'Sair da conta', icon: 'mdi-logout', action: logout }];
const itemsMenu = [
	{
		title: 'Página inicial',
		value: 'foo',
	},
	{
		title: 'Perfil',
		value: 'bar',
	},
	{
		title: 'Diagnóstico',
		value: 'bars',
	},
	{
		title: 'Sobre Nós',
		value: 'bars',
	},
];

async function logout(){
	try{
		await authService.logout()
		navigateTo("/")
	}
	catch(e){
		console.log(e)
	}
}

const group = ref(null);

watch(group, () => {
	drawer.value = false;
});
</script>
<style lang="scss">
img {
	width: 9.375rem;
	height: 2.813rem;
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
