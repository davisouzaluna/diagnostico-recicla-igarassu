import type { AxiosInstance } from 'axios';

class AuthService {
	constructor(private $axios: AxiosInstance) {}
	async register(name: string, email: string, password: string) {
		const { data } = await this.$axios.post('users', {
			name,
			email,
			password,
		});
		const { $userStore } = useNuxtApp();
		$userStore.user.name = data.name;
		$userStore.user.email = data.email;
		$userStore.user.id = data.id;
	}
	async login(email: string, password: string) {
		const { data } = await this.$axios.get('users', {
			params: {
				email,
				password,
			},
		});
		if (data.length > 0) {
			const { $userStore } = useNuxtApp();
			$userStore.user.id = data[0].id;
			$userStore.user.name = data[0].name;
			$userStore.user.email = data[0].email;
		} else {
			throw {
				// É assim que se dispara um erro :)
				msg: 'Email ou senha inválidos',
			};
		}
	}
	async logout() {
		const { $userStore } = useNuxtApp();
		$userStore.user.id = '';
		$userStore.user.name = '';
		$userStore.user.email = '';
	}
}
const { $axios } = useNuxtApp();
export default new AuthService($axios);
