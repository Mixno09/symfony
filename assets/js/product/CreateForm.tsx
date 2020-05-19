import React, {useState, useCallback, useEffect} from 'react';
import FormTextInput from "../common/FormTextInput";
import FormTextAria from "../common/FormTextAria";
import FormImageElement from "../common/FormImageElement";
import {generateSlug} from "../common/SlugUtil";
import api from "./API";
import {v4 as uuid} from "uuid";

function CreateProductForm() {
    const [data, setProduct] = useState({
        title: '',
        slug: '',
        description: '',
        image: null,
    });
    const [image, setImage] = useState('');
    const [errors, setErrors] = useState(EMPTY_ERRORS);
    const [isProcessed, setIsProcessed] = useState(false);
    const [isRedirect, setIsRedirect] = useState(false);

    useEffect(() => {
        if (! isProcessed) {
            return;
        }

        (async () => {
            try {
                const id = uuid();
                const response = await api.createProduct({...data, id});

                if (response.status === 201) {
                    location.href = `/admin/product/${encodeURIComponent(id)}/update`;
                    setErrors(EMPTY_ERRORS);
                    setIsRedirect(true);
                } else if (response.status === 422) {
                    setErrors(response.data.errors);
                } else {
                    console.log(response);
                    alert('Неизвестный формат ответа от сервера. Дополнительная информация в консоли');
                }
            } catch (e) {
                console.log(e);
                alert('Произошла ошибка. Дополнительная информация в консоли');
            } finally {
                setIsProcessed(false);
            }
        })();
    }, [isProcessed]);

    const onChangeTitle = useCallback((title) => {
        const slug = generateSlug(title);
        setProduct((data) => ({...data, title, slug}));
    }, []);
    const onChangeSlug = useCallback((slug) => {
        setProduct((data) => ({...data, slug}));
    }, []);
    const onChangeDescription = useCallback((description) => {
        setProduct((data) => ({...data, description}));
    }, []);
    const onChangeImage = useCallback((value) => {
        let file = null;
        let image = '';
        if (value instanceof File) {
            file = value;
            image = URL.createObjectURL(file);
        }
        setProduct((data) => ({...data, image: file}));
        setImage(image);
    }, []);
    const onSubmit = useCallback((event) => {
        event.preventDefault();
        setIsProcessed(true);
    }, []);

    return (
        <form onSubmit={onSubmit}>
            <FormTextInput
                label="Заголовок"
                value={data.title}
                errors={errors.title}
                isDisabled={isProcessed}
                onChange={onChangeTitle}
            />
            <FormTextInput
                label="Slug"
                value={data.slug}
                errors={errors.slug}
                isDisabled={isProcessed}
                onChange={onChangeSlug}
            />
            <FormTextAria
                label="Описание"
                value={data.description}
                errors={errors.description}
                isDisabled={isProcessed}
                onChange={onChangeDescription}
            />
            <FormImageElement
                label="Изображение"
                file={data.image}
                image={image}
                width="200px"
                height="200px"
                errors={errors.image}
                isDisabled={isProcessed}
                onChange={onChangeImage}
            />
            <div className="d-flex align-items-center">
                <button type="submit" className="btn btn-success btn-lg mr-1" disabled={isProcessed || isRedirect}>Создать</button>
                <a href={'/admin/product'} className="btn btn-light btn-lg mr-3">Отмена</a>
                { (isProcessed || isRedirect) &&
                <>
                    <div className="spinner-grow spinner-grow-sm text-info mr-1" />
                    <span className="text-info">{ isProcessed ? 'Обработка...' : 'Переход...' }</span>
                </>
                }
            </div>
        </form>
    )
}

const EMPTY_ERRORS = {
    title: null,
    slug: null,
    description: null,
    image: null,
}

export default CreateProductForm;
