import * as React from "react";
import uniqueId from 'lodash/uniqueId';
import FormElementError from "./FormElementError";

export interface OnChangeFileHandler {
    (file: File | null): void,
}

export interface FormFileProps {
    label: string,
    file: File | null,
    errors: string[],
    isDisabled: boolean,
    onChangeFile: OnChangeFileHandler,
}

const FormFile: React.FunctionComponent<FormFileProps> =
    ({
        label,
        file,
        errors,
        isDisabled,
        onChangeFile,
    }) => {
        const [id] = React.useState(
            uniqueId('FormFile')
        );
        const onChange = React.useCallback<React.ChangeEventHandler<HTMLInputElement>>(
            (event) => {
                let file: File | null = null;
                if (event.target.files instanceof FileList) {
                    file = event.target.files.item(0);
                }
                onChangeFile(file);
            },
            []
        );

        return (
            <div className="form-group">
                <label htmlFor={id}>
                    {label}
                    <FormElementError errors={errors}/>
                </label>
                <div className="custom-file">
                    <input
                        type="file"
                        id={id}
                        className={'custom-file-input' + (errors.length > 0 ? ' is-invalid' : '')}
                        disabled={isDisabled}
                        onChange={onChange}
                    />
                    <label
                        htmlFor={id}
                        className="custom-file-label"
                        data-browse="Обзор"
                    >
                        {file instanceof File ? file.name : ''}
                    </label>
                </div>
            </div>
        );
    };

export default FormFile;
