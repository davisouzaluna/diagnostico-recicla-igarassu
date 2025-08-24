import type { AxiosInstance } from 'axios';

class AuthService {
	constructor(private $axios: AxiosInstance) {}
	async register(name: string, email: string, password: string) {
		return this.$axios.post('users', {
			name,
			email,
			password,
		});
	}
	async login(email: string, password: string) {
		const { data } = await this.$axios.get('users', {
			params: {
				email,
				password,
			},
		});
		if (data.length > 0) {
		} else {
			throw {
				// É assim que se dispara um erro :)
				msg: 'Email ou senha inválidos',
			};
		}
	}
}
const { $axios } = useNuxtApp();
export default new AuthService($axios);
