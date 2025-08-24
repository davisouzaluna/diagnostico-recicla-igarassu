export default new (class CepDataService {
	async execute(cep: string) {
		const { data } = await $fetch('/api/location_by_cep', {
			params: {
				search: cep,
			},
		});
		return data;
	}
})();
