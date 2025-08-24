<template>
	<v-navigation-drawer
		color="primary"
		v-model=""
		:location="$vuetify.display.mobile ? 'bottom' : undefined"
		temporary
	>
		<v-list :items="items"></v-list>
	</v-navigation-drawer>
</template>

<script setup lang="ts">
const drawer = ref(false);

const items = [
	{
		title: 'Foo',
		value: 'foo',
	},
	{
		title: 'Bar',
		value: 'bar',
	},
	{
		title: 'Fizz',
		value: 'fizz',
	},
	{
		title: 'Buzz',
		value: 'buzz',
	},
];

const group = ref(null);

watch(group, () => {
	drawer.value = false;
});
</script>
