import useUserStore from '~/stores/useUserStore';

export default defineNuxtPlugin(() => {
	return {
		provide: {
			userStore: useUserStore(),
		},
	};
});
