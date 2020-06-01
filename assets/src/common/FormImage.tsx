import * as React from "react";
import FormFile, {OnChangeFileHandler as FormFileOnChangeFileHandler} from "./FormFile";

export interface OnChangeFileHandler extends FormFileOnChangeFileHandler {}

export interface FormImageProps {
    label: string,
    file: File | null,
    errors: string[],
    isDisabled: boolean,
    onChangeFile: OnChangeFileHandler,
    image: {
        src?: string,
        width?: string,
        height?: string,
    },
}

const FormImage: React.FunctionComponent<FormImageProps> =
    ({
        label,
        file,
        errors,
        isDisabled,
        onChangeFile,
        image = {},
    }) => {
        const style = React.useMemo(
            () => {
                const style: React.CSSProperties = {};
                if (typeof image.width === "string") {
                    style.maxWidth = image.width;
                }
                if (typeof image.height === "string") {
                    style.maxHeight = image.height;
                }
                return style;
            },
            [image.width, image.height]
        );
        const src = React.useMemo(
            () => {
                let src = image.src;
                if (file instanceof File && file.type.match(/^image\//)) {
                    src = URL.createObjectURL(file);
                }
                return src;
            },
            [image.src, file]
        );

        return (
            <>
                <FormFile
                    label={label}
                    file={file}
                    errors={errors}
                    isDisabled={isDisabled}
                    onChangeFile={onChangeFile}
                />
                {typeof src === "string" &&
                    <div className="form-group">
                        <img
                            src={src}
                            alt=""
                            className="img-thumbnail"
                            style={style}
                        />
                    </div>
                }
            </>
        );
    };

export default FormImage;
