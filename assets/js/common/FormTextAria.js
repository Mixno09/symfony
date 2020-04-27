import React, {useState, useCallback} from "react";
import _uniqueId from 'lodash/uniqueId';
import FormElementError from "./FormElementError";

export default function FormTextAria(props) {
    const [id] = useState(_uniqueId('textArea'));
    const onChange = useCallback((event) => props.onChange(event.target.value), []);

    return (
        <div className="form-group">
            <label htmlFor={id}>
                {props.label}
                <FormElementError errors={props.errors} />
            </label>
            <textarea
                className={'form-control' + (props.errors ? ' is-invalid' : '')}
                id={id}
                value={props.value}
                disabled={props.isDisabled}
                onChange={onChange}
            />
        </div>
    )
}
