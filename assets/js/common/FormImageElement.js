import React, {useCallback, useMemo} from "react";
import FormFileInput from "./FormFileInput";

export default function FormImageElement(props) {
    const style = useMemo(function () {
        const style = {};
        if (props.width) {
            style['maxWidth'] = props.width;
        }
        if (props.height) {
            style['maxHeight'] = props.height;
        }
        return style;
    }, [props.width, props.height]);

    const onChange = useCallback((value) => {
        let file = null;
        if (value instanceof File && value.type.match(/^image\//)) {
            file = value;
        }
        props.onChange(file);
    }, []);

    return (
        <>
            <FormFileInput
                label={props.label}
                file={props.file}
                errors={props.errors}
                isDisabled={props.isDisabled}
                onChange={onChange}
            />
            {props.image &&
                <div className="form-group">
                    <img
                        src={props.image}
                        alt=""
                        className="img-thumbnail"
                        style={style}
                    />
                </div>
            }
        </>
    )
}
