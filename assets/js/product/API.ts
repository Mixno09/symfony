import axios from "./../common/Axios"
import {AxiosResponse, AxiosStatic} from "axios";
import {makeFormData} from "../common/APIUtils";

class API {
    private readonly axios: AxiosStatic;

    constructor(axios: AxiosStatic) {
        this.axios = axios;
    }

    async createProduct(product: CreateProduct): Promise<AxiosResponse> {
        const data = makeFormData(product);

        return await this.axios.post('/api/products', data);
    }
}

interface CreateProduct {
    id: string;
    title: string;
    slug: string;
    description: string;
    image?: File;
}

const api = new API(axios);

export default api;
