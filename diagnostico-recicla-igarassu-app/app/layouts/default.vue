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
		</v-app-bar>
		<v-navigation-drawer
			color="primary"
			v-model="drawer"
			:location="$vuetify.display.mobile ? 'bottom' : undefined"
			temporary
		>
			<v-list :items="items"></v-list>
		</v-navigation-drawer>
		<main>
			<section>
				<slot />
			</section>
		</main>
		<LayoutAppFooter
			:isOpen="open"
			color="primary"
		/>
	</v-app>
</template>

<script setup lang="ts">
const drawer = ref(false);

const items = [
	{
		title: 'Página inicial',
		value: 'foo',
	},
	{
		title: 'Perfil',
		value: 'bar',
	},
	{
		title: 'Sobre Nós',
		value: 'bar',
	},
];

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
