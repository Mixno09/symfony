import * as React from "react";
import FormText, {OnChangeValueHandler as FormTextOnChangeValueHandler} from "../common/FormText";
import FormTextAria, {OnChangeValueHandler as FormTextAriaOnChangeValueHandler} from "../common/FormTextAria";
import FormImage, {OnChangeFileHandler as FormImageOnChangeFileHandler} from "../common/FormImage";
import SlugUtil from "../common/SlugUtil";
import api from "./API";
import {v4 as uuid} from "uuid";

type Data = {
    title: string,
    slug: string,
    description: string,
    image: File | null,
}

type Errors = {
    title: string[],
    description: string[],
    slug: string[],
    image: string[],
}

const EmptyErrors = {
    title: [],
    slug: [],
    description: [],
    image: [],
}

const CreateForm: React.FunctionComponent =
    () => {
        const [data, setData] = React.useState<Data>({
            title: '',
            slug: '',
            description: '',
            image: null,
        });
        const [errors, setErrors] = React.useState<Errors>(EmptyErrors);
        const [isProcessed, setIsProcessed] = React.useState(false);
        const [isRedirect, setIsRedirect] = React.useState(false);

        React.useEffect(() => {
            if (! isProcessed) {
                return;
            }

            (async () => {
                try {
                    const id = uuid();
                    const response = await api.createProduct({...data, id});

                    if (response.status === 201) {
                        location.href = `/admin/product/${encodeURIComponent(id)}/update`;
                        setErrors(EmptyErrors);
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

        const onChangeTitle = React.useCallback<FormTextOnChangeValueHandler>(
            (title) => {
                const slug = SlugUtil.makeSlug(title);
                setData((data) => ({...data, title, slug}));
            },
            []
        );
        const onChangeSlug = React.useCallback<FormTextOnChangeValueHandler>(
            (slug) => {
                setData((data) => ({...data, slug}));
            },
            []
        );
        const onChangeDescription = React.useCallback<FormTextAriaOnChangeValueHandler>(
            (description) => {
                setData((data) => ({...data, description}));
            },
            []
        );
        const onChangeFile = React.useCallback<FormImageOnChangeFileHandler>(
            (file) => {
                setData((data) => ({...data, image: file}));
            },
            []
        );
        const onSubmit = React.useCallback<React.FormEventHandler<HTMLFormElement>>(
            (event) => {
                event.preventDefault();
                setIsProcessed(true);
            },
            []
        );

        return (
            <form onSubmit={onSubmit}>
                <FormText
                    label="Заголовок"
                    value={data.title}
                    errors={errors.title}
                    isDisabled={isProcessed}
                    onChangeValue={onChangeTitle}
                />
                <FormText
                    label="Slug"
                    value={data.slug}
                    errors={errors.slug}
                    isDisabled={isProcessed}
                    onChangeValue={onChangeSlug}
                />
                <FormTextAria
                    label="Описание"
                    value={data.description}
                    errors={errors.description}
                    isDisabled={isProcessed}
                    onChangeValue={onChangeDescription}
                />
                <FormImage
                    label="Изображение"
                    file={data.image}
                    image={{width: "200px", height: "200px"}}
                    errors={errors.image}
                    isDisabled={isProcessed}
                    onChangeFile={onChangeFile}
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
        );
    };

export default CreateForm;
