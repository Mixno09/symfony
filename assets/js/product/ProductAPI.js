import { v4 as uuid } from 'uuid';
import axios from 'axios';

export async function createProduct(data) {
    const id = uuid();
    data['id'] = id;

    const response = await axios.post(
        '/api/products',
        makeFormData(data)
    );

    const result = {...response.data};
    if (result.type === 'success') {
        result.id = id;
    }
    return result;
}

function makeFormData(data)
{
    const formData = new FormData();
    for (let name in data) {
        if (data.hasOwnProperty(name)) {
            formData.append(name, data[name]);
        }
    }
    return formData;
}
