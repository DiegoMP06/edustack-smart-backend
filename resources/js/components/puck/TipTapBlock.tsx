import type { ComponentConfig } from '@puckeditor/core';
import TipTapEditor, { TipTapViewer } from '@/components/tiptap/TipTapEditor';

type TipTapBlockProps = {
    content: string;
};

export default function TipTapBlock({ content }: TipTapBlockProps) {
    return (
        <div className="my-2">
            <TipTapViewer content={content} />
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
        content: {
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
        content: '<p>Escribe aquí...</p>',
    },
    render: TipTapBlock,
};
