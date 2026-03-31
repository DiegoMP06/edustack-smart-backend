import { Code, Plus } from 'lucide-react';
import { useEffect, useState } from 'react';
import type { FC } from 'react';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Input } from '@/components/ui/shadcn/input';
import {
    Item,
    ItemContent,
    ItemMedia,
    ItemTitle,
} from '@/components/ui/shadcn/item';

interface TechStackInputProps {
    onChange: (value: string[]) => void;
    value: string[];
}

const TechStackInput: FC<TechStackInputProps> = ({ onChange, value }) => {
    const [items, setItems] = useState<
        {
            id: number;
            value: string;
        }[]
    >(() =>
        value.map((tech) => ({
            id: Date.now() * Math.random(),
            value: tech,
        })),
    );
    const [item, setItem] = useState('');
    const [error, setError] = useState<undefined | string>(undefined);

    const handleAddItem = () => {
        if (item.trim() !== '') {
            setItems([...items, { id: Date.now(), value: item.trim() }]);
            setItem('');
            setError(undefined);
        } else {
            setError('El campo no puede estar vacío');
        }
    };


    const handlePaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
        const pasteData = e.clipboardData.getData('text');

        if (pasteData.includes(',')) {
            e.preventDefault();

            const newTechs = pasteData
                .split(',')
                .map(tech => tech.trim())
                .filter(tech => tech !== '');

            const newItems = newTechs.map(tech => ({
                id: Date.now() * Math.random(),
                value: tech
            }));

            setItems([...items, ...newItems]);
            setItem('');
            setError(undefined);
        }
    };

    const handleRemoveItem = (id: number) => {
        setItems(items.filter((item) => item.id !== id));
    };

    useEffect(() => {
        onChange(items.map((item) => item.value));
    }, [items, onChange]);

    return (
        <div className="grid grid-cols-1 gap-4">
            <div className="grid gap-2">
                <div className="flex gap-2">
                    <Input
                        placeholder="Ingresa una tecnología"
                        className="flex-1"
                        value={item}
                        onPaste={handlePaste}
                        onChange={(e) => setItem(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                handleAddItem();
                            }
                        }}
                    />

                    <Button
                        className="flex-0"
                        variant="secondary"
                        type="button"
                        onClick={handleAddItem}
                    >
                        <Plus />
                    </Button>
                </div>

                <InputError message={error} />
            </div>

            {items.length > 0 && (
                <div className="flex flex-wrap gap-2">
                    {items.map((item) => (
                        <Item
                            variant="muted"
                            className="gap-1 p-0 w-fit overflow-hidden"
                            key={item.id}
                            onDoubleClick={() => handleRemoveItem(item.id)}
                        >
                            <ItemMedia variant="icon" className="p-2 bg-accent">
                                <Code className="size-6" />
                            </ItemMedia>
                            <ItemContent className="p-2">
                                <ItemTitle>{item.value}</ItemTitle>
                            </ItemContent>
                        </Item>
                    ))}
                </div>
            )}
        </div>
    );
};

export default TechStackInput;
