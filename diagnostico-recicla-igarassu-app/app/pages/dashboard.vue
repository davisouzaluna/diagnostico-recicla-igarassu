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
		<Map :locations="locations" />
	</section>
</template>

<script setup lang="ts">
import locationsService from '~/services/locationsService';
import type { LocationType } from '~/types/location';

const locations = ref<LocationType[]>([]);

async function getLocations() {
	try {
		const data = await locationsService.getLocations();
		locations.value.push(...data);
	} catch (e) {
		alert(e.msg);
	}
}
onMounted(() => getLocations());
onActivated(() => getLocations());
</script>
