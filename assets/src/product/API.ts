import axios from "./../common/Axios"
import {AxiosResponse, AxiosStatic} from "axios";
import APIUtils from "../common/APIUtils";

interface CreateProduct {
    id: string;
    title: string;
    slug: string;
    description: string;
    image: File | null;
}

class API {
    private readonly axios: AxiosStatic;

    constructor(axios: AxiosStatic) {
        this.axios = axios;
    }

    async createProduct(product: CreateProduct): Promise<AxiosResponse> {
        const data = APIUtils.makeFormData(product);

        return await this.axios.post('/api/products', data);
    }
}

export default new API(axios);
