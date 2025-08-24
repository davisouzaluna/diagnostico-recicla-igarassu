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
			style="position: relative"
			v-for="(item, i) in items"
			:key="i"
			cover
		>
			<div>
				<div
					:style="{ backgroundImage: `url(${item.src})` }"
					class="svg-image"
				/>
				<div :class="['carousel-text', item.position]">
					{{ item.title }}
				</div>
				<div class="overlay-button">
					<v-btn
						color="primary"
						class="button mt-4"
						@click="alert('Botão clicado!')"
					>
						Saiba mais
					</v-btn>
				</div>
			</div>
		</v-carousel-item>
	</v-carousel>
</template>

<script setup>
import image3 from '~/assets/images/image3.svg';
import image1 from '~/assets/images/image1.svg';
import image2 from '~/assets/images/image2.svg';
const items = [
	{
		type: 'svg',
		src: image1,
		title: 'Você sabia que...',
		position: 'center',
		hasButton: false,
	},
	{
		type: 'svg',
		src: image3,
		title: 'O descarte incorreto de resíduos contribui para enchentes e doenças em nosso bairro.',
		position: 'bottom-left',
		hasButton: true,
	},
	{
		type: 'svg',
		src: image2,
		title: "Mas o que muitos veem como 'lixo' é, na verdade, trabalho e renda para muitas famílias.",
		position: 'bottom-left',
		hasButton: false,
	},
];
</script>

<style scoped lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Cal+Sans&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');

.carousel-content {
	color: aliceblue;
	height: 80vh !important;
	width: 100vw;
	margin: 0;
	margin-bottom: auto;
	border-radius: 0;
	overflow: hidden;
	top: 9vh;
}
.svg-image {
	width: 100vw;
	height: 100vh; /* ocupa toda altura do carousel-item */
	background-size: cover; /* preenche mantendo proporção */
	background-position: center;
	background-repeat: no-repeat;
	position: relative;
	z-index: 0;
}
.carousel-text {
	position: absolute;
	color: white;
	backdrop-filter: blur(1px);
	-webkit-backdrop-filter: blur(1px);
	width: 45vw;
	font-size: 3em;
	font-family: 'DM Sans', sans-serif;
	font-optical-sizing: auto;
	font-weight: bold;
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
.overlay-button {
	position: absolute;
	width: 100%;
	height: 100%;
	z-index: 10;
}
.button {
	z-index: 20;
}
@media (max-width: 600px) {
	.carousel-content {
		height: 20vh !important;
	}

	.carousel-text {
		width: 80vw;
		font-size: 1rem;
		padding: 0.5rem;
	}

	.bottom-left {
		bottom: 2rem;
		left: 1rem;
	}

	.center {
		width: 90vw;
		font-size: 1.5em;
		text-align: center;
	}
}
</style>
