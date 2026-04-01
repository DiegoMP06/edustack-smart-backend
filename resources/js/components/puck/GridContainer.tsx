import type { ComponentConfig, SlotComponent } from '@puckeditor/core';

type GridContainerProps = {
    columns: number;
    gap: number;
    items: { content: SlotComponent }[];
    stackOnMobile: boolean;
};

export default function GridContainer({
    columns,
    gap,
    items,
    stackOnMobile,
}: GridContainerProps) {
    const containerClass = stackOnMobile
        ? `grid grid-cols-1 md:grid-container-${columns}`
        : `grid grid-container-${columns}`;

    return (
        <div className={containerClass} style={{ gap }}>
            <style>{`
                .grid-container-${columns} {
                    grid-template-columns: repeat(${columns}, minmax(0, 1fr));
                }
            `}</style>
            {items.map((item, i) => (
                <div key={`grid-item-${i}`}>{item.content()}</div>
            ))}
        </div>
    );
}

export const GridContainerConfig: ComponentConfig<GridContainerProps> = {
    label: 'Contenedor de elementos',
    fields: {
        columns: {
            min: 1,
            max: 12,
            type: 'number',
            label: 'Columnas',
            placeholder: 'Escribe aquí las columnas...',
        },
        items: {
            type: 'array',
            max: 20,
            getItemSummary: (item, i) => `Elemento ${Number(i) + 1}`,
            arrayFields: {
                content: { type: 'slot' },
            },
            defaultItemProps: {} as { content: SlotComponent },
        },
        gap: {
            min: 0,
            type: 'number',
            label: 'Espaciado',
            placeholder: 'Escribe aquí el espaciado...',
        },
        stackOnMobile: {
            type: 'radio',
            label: 'Colapsar en mobile',
            options: [
                { label: 'Sí', value: true },
                { label: 'No', value: false },
            ],
        },
    },
    defaultProps: {
        gap: 0,
        columns: 2,
        items: [],
        stackOnMobile: false,
    },
    render: GridContainer,
};
