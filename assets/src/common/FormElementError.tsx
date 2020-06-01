import * as React from "react";

export interface FormElementErrorProps {
    errors: string[],
}

const FormElementError: React.FunctionComponent<FormElementErrorProps> = ({errors}) => (
    <>
        {errors.map((message, key) => (
            <span key={key} className="invalid-feedback d-block">
                <span className="d-block">
                    <span className="form-error-icon badge badge-danger text-uppercase">Ошибка</span>
                    <span className="form-error-message">{message}</span>
                </span>
            </span>
        ))}
    </>
);

export default FormElementError;
