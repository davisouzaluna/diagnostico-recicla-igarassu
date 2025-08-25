import type { AxiosInstance } from 'axios';
import type { LocationType } from '~/types/location';

class LocationService {
	constructor(private $axios: AxiosInstance) {}
	async register(location: LocationType) {
		try {
			await this.$axios.post('locations', location);
		} catch (e) {
			console.log(e);
			throw {
				msg: 'Não foi possível registrar disponibilidade, verifique os dados inseridos',
			};
		}
	}
	async getLocations(): Promise<LocationType[]> {
		try {
			const { data } = await this.$axios.get('locations');
			return data;
		} catch (e) {
			console.log(e);
			throw {
				msg: 'Houve um problema ao buscar os pontos registrados',
			};
		}
	}
}
const { $axios } = useNuxtApp();
export default new LocationService($axios);
