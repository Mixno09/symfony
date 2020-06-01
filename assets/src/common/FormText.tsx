import * as React from "react";
import uniqueId from "lodash/uniqueId";
import FormElementError from "./FormElementError";

export interface OnChangeValueHandler {
    (value: string): void,
}

export interface FormTextProps {
    label: string,
    value: string,
    errors: string[],
    isDisabled: boolean,
    onChangeValue: OnChangeValueHandler,
}

const FormText: React.FunctionComponent<FormTextProps> =
    ({
         label,
         value,
         errors,
         isDisabled,
         onChangeValue,
    }) => {
        const [id] = React.useState(
            uniqueId('FormText')
        );
        const onChange = React.useCallback<React.ChangeEventHandler<HTMLInputElement>>(
            (event) => onChangeValue(event.target.value),
            []
        );

        return (
            <div className="form-group">
                <label htmlFor={id}>
                    {label}
                    <FormElementError errors={errors}/>
                </label>
                <input
                    type="text"
                    className={'form-control' + (errors.length > 0 ? ' is-invalid' : '')}
                    id={id}
                    value={value}
                    disabled={isDisabled}
                    onChange={onChange}
                />
            </div>
        );
    };

export default FormText;
