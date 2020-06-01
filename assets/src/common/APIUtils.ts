export class APIUtils {
    makeFormData(data: object): FormData {
        const formData = new FormData();
        for (let name in data) {
            if (! data.hasOwnProperty(name)) {
                continue;
            }

            const value = data[name];
            if (value instanceof File) {
                formData.append(name, value, value.name);
            } else {
                formData.append(name, value);
            }
        }
        return formData;
    }
}

export default new APIUtils();
