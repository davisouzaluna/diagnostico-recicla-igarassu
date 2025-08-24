<template>
	<section>
		<v-container class="d-flex flex-row justify-end">
			<v-btn
				color="primary"
				prepend-icon="mdi-map-marker"
				@click="navigateTo('/location/create')"
			>
				Registrar disponibilidade
			</v-btn>
		</v-container>
		<keep-alive>
			<Map />
		</keep-alive>
	</section>
</template>

<script setup lang="ts">
definePageMeta({
	keepalive: true, 
	key: "dashboard"
})
</script>
