import React, {useRef, useState, useCallback} from "react";
import _uniqueId from 'lodash/uniqueId';
import FormElementError from "./FormElementError";

export default function FormTextInput(props) {
    const inputElement = useRef(null);
    const [id] = useState(_uniqueId('input'));

    const onChange = useCallback(() => {
        let file = inputElement.current.files[0];
        if (! file instanceof File) {
            file = null;
        }
        props.onChange(file);
    }, []);

    return (
        <div className="form-group">
            <label htmlFor={id}>
                {props.label}
                <FormElementError errors={props.errors} />
            </label>
            <div className="custom-file">
                <input
                    type="file"
                    id={id}
                    className={'custom-file-input' + (props.errors ? ' is-invalid' : '')}
                    ref={inputElement}
                    disabled={props.isDisabled}
                    onChange={onChange}
                />
                <label
                    htmlFor={id}
                    className="custom-file-label"
                    data-browse="Обзор"
                >
                    { props.file instanceof File ? props.file.name : '' }
                </label>
            </div>
        </div>
    )
}
