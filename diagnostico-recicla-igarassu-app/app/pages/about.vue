<template>
    <v-btn 
    prepend-icon="mdi-arrow-left"
    variant="text" 
    color="primary"
    @click="navigateTo('/dashboard')"
    >
    Voltar
    </v-btn>
    <v-card color="surface" class="pa-16">
        <div class="d-flex justify-center mb-8">
            <h1>Sobre o Projeto</h1>
        </div>
        <p>
        A gestão de resíduos sólidos urbanos no Brasil continua sendo um dos maiores desafios socioambientais da atualidade. Apesar da existência de marcos legais como a Política Nacional de Resíduos Sólidos (PNRS), instituída pela Lei nº 12.305/2010, muitos municípios ainda não implementaram ações efetivas de coleta seletiva, logística reversa ou inclusão das cooperativas de catadores.
      </p>
      <p>
        Historicamente, os catadores de materiais recicláveis foram invisibilizados pelo poder público, mesmo desempenhando um papel estratégico na coleta seletiva. A legislação reconhece esses trabalhadores como agentes ambientais, mas a distância entre o texto legal e sua aplicação prática permanece significativa.
      </p>
      <p>
        Estudos recentes, como o de Silva et al. (2023), apontam que a falta de integração entre os diversos atores — governo, cooperativas, cidadãos e empresas — e a ausência de planejamento baseado em dados contribuem para uma gestão ineficaz, com desperdício de recursos e perda de valor socioeconômico dos resíduos recicláveis.
      </p>
      <p>
        Na Região Metropolitana do Recife (RMR), composta por 15 municípios, os problemas são ainda mais evidentes: coleta irregular, descarte inadequado, ausência de políticas de coleta seletiva e baixa articulação com cooperativas. Essa realidade compromete diretamente a saúde pública, o meio ambiente e a qualidade de vida da população.
      </p>
      <p>
        O Decreto nº 7.404/2010, que regulamenta a PNRS, determina prioridade para a participação de cooperativas nos sistemas de coleta seletiva. No entanto, essa diretriz ainda é negligenciada em diversas localidades.
      </p>
      <p>
        A Organização das Nações Unidas (ONU) destaca que a gestão sustentável de resíduos está diretamente ligada aos Objetivos de Desenvolvimento Sustentável (ODS), especialmente o ODS 11 (cidades sustentáveis) e o ODS 12 (consumo responsável). O Brasil, ao ratificar a Agenda 2030 e participar de conferências como a COP 26 e a futura COP 30, assumiu o compromisso de promover economias circulares e políticas de resíduos zero.
      </p>
      <p>
        Este projeto nasce da urgência de repensar os modelos tradicionais de gestão de resíduos no Brasil. Propõe soluções baseadas em evidências e tecnologias acessíveis, com foco na valorização dos catadores, na articulação entre os atores da cadeia e na construção de cidades mais justas, limpas e sustentáveis.
      </p>
    </v-card>
</template>
<script setup lang="ts">
</script>