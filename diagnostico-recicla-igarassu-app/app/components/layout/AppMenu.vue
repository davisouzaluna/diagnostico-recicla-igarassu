<template>
	<v-card
		v-if="useCookie('token').value"
		color="primary"
		width="200"
	>
		<v-navigation-drawer
			color="primary"
			expand-on-hover
			permanent
			dark
			rail
			v-model="drawer"
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
					prepend-icon="mdi-user"
					title="Perfil"
					@click="navigateTo()"
					class="bg-primary"
				>
				</v-list-item>
				<v-list-item
					prepend-icon="mdi-info"
					title="Sobre Nós"
					@click="navigateTo('/dashboard')"
					class="bg-primary"
				>
				</v-list-item>
				<v-list-item
					prepend-icon="mdi-chart"
					title="Diagnóstico"
					@click="navigateTo('/diagnostic')"
					class="bg-primary"
				>
				</v-list-item>
			</v-list>
		</v-navigation-drawer>
	</v-card>
</template>

<script setup lang="ts">
const drawer = ref(true);
</script>
