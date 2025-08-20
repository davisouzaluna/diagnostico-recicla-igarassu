<template>
  <v-carousel  
    class="carousel-content"
    show-arrows="hover"
    progress="primary"
    cycle
    :interval="6000"
    hide-delimiter-background
    color="primary"
    prev-icon="mdi-chevron-left"
    next-icon="mdi-chevron-right"
  >
  <v-carousel-item
    v-for="(item,i) in items"
      :key="i"
      cover
  >
  <div>
    <img :src="item.src" class="svg-image">
    <div :class="['carousel-text', item.position]">
       {{ item.title }}
    </div>
  </div>
  </v-carousel-item>
</v-carousel>
<h1>holla manito</h1>
<br/>
<br/>
<br/>
<h1>só um teste</h1>
</template>

<script setup>
import image3 from '~/assets/images/image3.svg'
import image1 from '~/assets/images/image1.svg'
import image2 from '~/assets/images/image2.svg'
const items = [
      {
      type: 'svg',
      src: image1,
      title:'Você sabia que...',
      position: 'center',
    },
    {
      type: 'svg',
      src: image3,
      title:'O descarte incorreto de resíduos contribui para enchentes e doenças em nosso bairro.',
      position: 'bottom-left',
    },
    {
      type: 'svg',
      src: image2,
      title:"Mas o que muitos veem como 'lixo' é, na verdade, trabalho e renda para muitas famílias.",
      position: 'bottom-left',
    },
  
  ]
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Cal+Sans&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

.carousel-content{
  color:aliceblue;
  height: 90vh !important;
  width: 100vw;
  margin: 0;
  border-radius: 0;
  overflow: hidden;
  top: 7vh;
}
.svg-image {
  width: 100%;
  height: 100%;
}
.carousel-text {
  position: absolute;
  color: white;
  backdrop-filter: blur(1px);
  -webkit-backdrop-filter: blur(1px);
  width: 45vw;
  font-size: 3rem;
  font-family: "DM Sans", sans-serif;
  font-optical-sizing: auto;
  font-weight:bold;
  font-style: normal;

}
.bottom-left {
  bottom: 10rem;
  left: 5rem;
}
.top-right {
  top: 1rem;
  right: 1rem;
}
.center {
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.v-carousel__controls .v-btn--icon {
  color: #00ff00 !important; /* verde */
  opacity: 0;
}
</style>