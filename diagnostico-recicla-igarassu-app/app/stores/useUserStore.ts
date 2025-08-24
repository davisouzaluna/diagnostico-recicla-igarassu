import { defineStore } from 'pinia';

export default defineStore('user', () => {
	const user = ref({
		id: '',
		name: '',
		email: '',
	});
	return { user };
});
