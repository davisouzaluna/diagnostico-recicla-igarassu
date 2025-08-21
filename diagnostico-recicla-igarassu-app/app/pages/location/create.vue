<template>
    <v-container>
        <v-card color="surface">
            <v-container class="pa-8">
                <h1>Registrar disponibilidade de material</h1>
                <Map class="ma-auto" style="height: 50vh;width: auto"/>
            </v-container>
            <v-form>
                <v-label for=""></v-label>
                <v-select
                    v-model="selectedSize"
                    variant="outlined"
                    :items="['Saco pequeno','Saco grande','Caixa']"
                        >
                    </v-select>
                    <v-container class="d-flex flex-row justify-end ga-2">
                        <v-btn color="primary" variant="outlined" @click="navigateTo('/dashboard')">Cancelar</v-btn>
                        <v-btn color="primary">Registrar</v-btn>
                    </v-container>
            </v-form>
        </v-card>
    </v-container>
</template>

<script setup lang="ts">
const selectedSize= ref('Selecione a quantidade')
</script>

