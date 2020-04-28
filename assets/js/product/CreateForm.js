import React, {useState, useCallback, useEffect} from "react";
import FormTextInput from "../common/FormTextInput";
import FormTextAria from "../common/FormTextAria";
import FormImageElement from "../common/FormImageElement";
import {generateSlug} from "../common/SlugUtil";
import {createProduct} from "./ProductAPI";

export default function CreateProductForm() {
    const [data, setData] = useState({
        title: '',
        slug: '',
        description: '',
        image: null,
    });
    const [image, setImage] = useState('');
    const [errors, setErrors] = useState({});
    const [isProcessed, setIsProcessed] = useState(false);
    const [isRedirect, setIsRedirect] = useState(false);

    useEffect(() => {
        if (! isProcessed) {
            return;
        }

        (async () => {
            try {
                const result = await createProduct(data);

                if (result.type === 'success') {
                    location.href = ('/admin/product/' + encodeURIComponent(result.id) + '/update');
                    setErrors({});
                    setIsRedirect(true);
                } else if (result.type === 'validation_error') {
                    setErrors(result.errors);
                } else {
                    console.log(result);
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
        setData((data) => ({...data, title, slug}));
    }, []);
    const onChangeSlug = useCallback((slug) => {
        setData((data) => ({...data, slug}));
    }, []);
    const onChangeDescription = useCallback((description) => {
        setData((data) => ({...data, description}));
    }, []);
    const onChangeImage = useCallback((value) => {
        let file = null;
        let image = '';
        if (value instanceof File) {
            file = value;
            image = URL.createObjectURL(file);
        }
        setData((data) => ({...data, image: file}));
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
