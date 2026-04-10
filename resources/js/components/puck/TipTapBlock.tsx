import type { ComponentConfig } from '@puckeditor/core';
import TipTapEditor, { TipTapViewer } from '@/components/tiptap/TipTapEditor';

type TipTapBlockProps = {
    html: string;
};

export default function TipTapBlock({ html }: TipTapBlockProps) {
    return (
        <div className="my-2">
            <TipTapViewer content={html} />
        </div>
    );
}

function TipTapBlockField({
    value,
    onChange,
}: {
    value: string;
    onChange: (value: string) => void;
}) {
    return (
        <TipTapEditor
            value={value}
            onChange={onChange}
            placeholder="Escribe el contenido..."
            minHeight="180px"
            maxHeight="400px"
        />
    );
}

export const TipTapBlockConfig: ComponentConfig<TipTapBlockProps> = {
    label: 'Editor enriquecido',
    fields: {
        html: {
            type: 'custom',
            render: ({ value, onChange }) => (
                <TipTapBlockField
                    value={(value as string) ?? ''}
                    onChange={onChange}
                />
            ),
        },
    },
    defaultProps: {
        html: '<p>Escribe aquí...</p>',
    },
    render: TipTapBlock,
};
