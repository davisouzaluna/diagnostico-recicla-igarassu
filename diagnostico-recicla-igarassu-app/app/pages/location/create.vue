<template>
	<v-container>
		<v-card color="surface">
			<v-container class="pa-8">
				<h1 class="ml-auto mr-auto">Registrar disponibilidade de material</h1>
				<Map
					class="ma-auto"
					style="height: 50vh; width: auto"
				/>
			</v-container>
			<v-form>
				<v-label for=""></v-label>
				<v-select
					v-model="selectedSize"
					variant="outlined"
					:items="['Saco pequeno', 'Saco grande', 'Caixa']"
				>
				</v-select>
				<v-label for="cep">CEP:</v-label>
				<v-mask-input
					mask="#####-###"
					variant="outlined"
					placeholder="Digite seu cep"
					id="cep"
					v-model="cepInfo.cep"
				></v-mask-input>

				<v-label for="rua">Rua:</v-label>
				<v-text-field
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
					id="rua"
					v-model="cepInfo.rua"
				></v-text-field>

				<v-label for="numero">Número:</v-label>
				<v-text-field
					variant="outlined"
					placeholder="Digite o número da casa"
					id="numero"
					v-model="cepInfo.numero"
				></v-text-field>

				<v-label for="bairro">Bairro:</v-label>
				<v-text-field
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
					id="bairro"
					v-model="cepInfo.bairro"
				></v-text-field>

				<v-label for="cidade">Cidade:</v-label>
				<v-text-field
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
					id="cidade"
					v-model="cepInfo.cidade"
				></v-text-field>

				<v-label for="estado">Estado:</v-label>
				<v-text-field
					:disabled="isDisable"
					variant="outlined"
					placeholder=""
					id="estado"
					v-model="cepInfo.estado"
				></v-text-field>

				<v-container class="d-flex flex-row justify-end ga-2">
					<v-btn
						color="primary"
						variant="outlined"
						@click="navigateTo('/dashboard')"
						>Cancelar</v-btn
					>
					<v-btn color="primary">Registrar</v-btn>
				</v-container>
			</v-form>
		</v-card>
	</v-container>
</template>

<script setup lang="ts">
import cepDataService from '~/services/cepDataService';

const selectedSize = ref('Selecione a quantidade');
const isDisable = ref(true);

const cepInfo = ref({
	cep: '',
	rua: '',
	numero: '',
	bairro: '',
	cidade: '',
	estado: '',
});

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
					cepInfo.value.rua = data.address;
					cepInfo.value.bairro = data.district;
					cepInfo.value.cidade = data.city;
					cepInfo.value.estado = data.state;
					isDisable.value = false;
				} catch (e) {
					if (e?.response?.status === 400) {
						alert('CEP inválido!');
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
