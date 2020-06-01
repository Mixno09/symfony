import * as React from "react";
import uniqueId from 'lodash/uniqueId';
import FormElementError from "./FormElementError";

export interface OnChangeValueHandler {
    (value: string): void,
}

export interface FormTextAriaProps {
    label: string,
    value: string,
    errors: string[],
    isDisabled: boolean,
    onChangeValue: OnChangeValueHandler,
}

const FormTextAria: React.FunctionComponent<FormTextAriaProps> =
    ({
         label,
         value,
         errors,
         isDisabled,
         onChangeValue,
    }) => {
        const [id] = React.useState(
            uniqueId('FormTextArea')
        );
        const onChange = React.useCallback<React.ChangeEventHandler<HTMLTextAreaElement>>(
            (event) => onChangeValue(event.target.value),
            []
        );

        return (
            <div className="form-group">
                <label htmlFor={id}>
                    {label}
                    <FormElementError errors={errors}/>
                </label>
                <textarea
                    className={'form-control' + (errors.length > 0 ? ' is-invalid' : '')}
                    id={id}
                    value={value}
                    disabled={isDisabled}
                    onChange={onChange}
                />
            </div>
        );
    };

export default FormTextAria;
