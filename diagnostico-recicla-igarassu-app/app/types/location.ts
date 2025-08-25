export type Coordinate = {
	lat: number;
	lng: number;
};
export interface LocationType extends Coordinate {
	id: string;
	material_info: MaterialInfoType;
	cep_info: LocationCepInfoType;
}
export type MaterialInfoType = {
	type: 'Selecione o tipo de material' | 'Metal' | 'Plástico' | 'Papelão';
	size: 'Selecione a quantidade' | 'Saco pequeno' | 'Saco grande' | 'Caixa';
};
export type LocationCepInfoType = {
	cep: string;
	rua: string;
	numero: string;
	contato: string;
	bairro: string;
	cidade: string;
	estado: string;
	referencia: string;
};
