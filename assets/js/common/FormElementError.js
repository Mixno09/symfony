import React from "react";

export default function FormElementError(props) {
    if (! props.errors) {
        return (<div/>);
    }
    return (
        props.errors.map((message, key) => (
            <span key={key} className="invalid-feedback d-block">
                <span className="d-block">
                    <span className="form-error-icon badge badge-danger text-uppercase">Ошибка</span>
                    <span className="form-error-message">{message}</span>
                </span>
            </span>
        ))
    )
}
