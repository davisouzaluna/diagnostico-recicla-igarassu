<template>
	<v-container>
		<v-card color="surface">
			<v-container class="pa-8">
				<h1 class="ml-auto mr-auto">Registrar disponibilidade de material</h1>
					<Map
						class="ma-auto"
						style="height: 50vh; width: auto"
						:selected-point="selectedPoint"
					/>
			</v-container>
			<v-form @submit.prevent="submit()">
				<v-label for="type">Tipo de material:</v-label>
				<v-select
					id="type"
					v-model="materialInfo.type"
					variant="outlined"
					:items="['Metal', 'Plástico', 'Papelão']"
				/>
				<v-label for="size">Quantidade disponível:</v-label>
				<v-select
					id="size"
					v-model="materialInfo.size"
					variant="outlined"
					:items="['Saco pequeno', 'Saco grande', 'Caixa']"
				/>
				
				<v-label for="cep">CEP:</v-label>
				<v-mask-input
				id="cep"
				v-model="cepInfo.cep"
					mask="#####-###"
					variant="outlined"
					placeholder="Digite seu cep"
					:error="cepError"
					:error-messages="cepError ? 'CEP inválido' : []"
				/>

				<v-label for="rua">Rua:</v-label>
				<v-text-field
				id="rua"
				v-model="cepInfo.rua"
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
				/>

				<v-label for="numero">Número:</v-label>
				<v-text-field
					id="numero"
					v-model="cepInfo.numero"
					variant="outlined"
					placeholder="Digite o número da casa"
				/>

				<v-label for="bairro">Bairro:</v-label>
				<v-text-field
					id="bairro"
					v-model="cepInfo.bairro"
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
				/>

				<v-label for="cidade">Cidade:</v-label>
				<v-text-field
					id="cidade"
					v-model="cepInfo.cidade"
					:disabled="isDisable"
					placeholder=""
					variant="outlined"
				/>

				<v-label for="estado">Estado:</v-label>
				<v-text-field
				id="estado"
				v-model="cepInfo.estado"
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
				/>
				<v-label for="contato">Contato:</v-label>
				<v-mask-input
				id="contato"
				v-model="cepInfo.contato"
					mask="(##) #####-####"
					variant="outlined"
				/>
				<v-label for="reference_point">Informações adicionais:</v-label>
				<v-textarea 
				id="reference_point"
				v-model="cepInfo.referencia"
				variant="outlined"
				/>
				<v-container class="d-flex flex-row justify-end ga-2">
					<v-btn
						color="primary"
						variant="outlined"
						@click="navigateTo('/dashboard')"
						>Cancelar</v-btn
					>
					<v-btn type="submit" color="primary">Registrar</v-btn>
				</v-container>
			</v-form>
		</v-card>
	</v-container>
</template>

<script setup lang="ts">
import cepDataService from '~/services/cepDataService';
import locationsService from '~/services/locationsService';
import type { Coordinate, LocationCepInfoType, MaterialInfoType } from '~/types/location';

const isDisable = ref(true);
const cepError = ref(false);
const materialInfo = ref<MaterialInfoType>({
	size: 'Selecione a quantidade',
	type: 'Selecione o tipo de material'
})
const selectedPoint = ref({
	lat: 0,
	lng: 0
})

async function addPoint(point:Coordinate){
	selectedPoint.value.lat = point.lat;
	selectedPoint.value.lng = point.lng;
}

const cepInfo = ref<LocationCepInfoType>({
	cep: '',
	rua: '',
	contato: '',
	numero: '',
	bairro: '',
	cidade: '',
	estado: '',
	referencia: ''
});
async function submit(){
	try{
		await locationsService.register({
			lat: selectedPoint.value.lat,
			lng: selectedPoint.value.lng,
			material_info: materialInfo.value,
			cep_info: cepInfo.value
		})
		navigateTo('/dashboard')
	}
	catch(e){
		alert(e.msg)
	}
}
let debounce: NodeJS.Timeout | null = null;

watch(
	cepInfo,
	({ cep: $new }) => {
		if ($new) {
			if (debounce) {
				clearTimeout(debounce);
			}
			debounce = setTimeout(async () => {
				try {
					const data = await cepDataService.execute($new);
					addPoint({
						lat: data.lat,
						lng: data.lng
					})
					cepInfo.value.rua = data.address;
					cepInfo.value.bairro = data.district;
					cepInfo.value.cidade = data.city;
					cepInfo.value.estado = data.state;
					isDisable.value = false;
					cepError.value= false;
				} catch (e) {
					if (e?.response?.status === 400) {
						cepError.value = true
						cepInfo.value.rua = "";
						cepInfo.value.bairro = "";
						cepInfo.value.cidade = "";
						cepInfo.value.estado = "";
						isDisable.value = true;
					}
				}
			}, 1000);
		}
	},
	{
		deep: true,
	},
);
</script>
