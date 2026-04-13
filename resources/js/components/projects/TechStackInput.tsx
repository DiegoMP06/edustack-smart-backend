import { Code, Pencil, Plus, Trash } from 'lucide-react';
import { useEffect, useState } from 'react';
import type { FC } from 'react';
import InputError from '@/components/ui/app/input-error';
import { Button } from '@/components/ui/shadcn/button';
import { Input } from '@/components/ui/shadcn/input';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemMedia,
    ItemTitle,
} from '@/components/ui/shadcn/item';

interface TechStackInputProps {
    onChange: (value: string[]) => void;
    value: string[];
}

type TechItem = {
    id: number;
    value: string;
}

const TechStackInput: FC<TechStackInputProps> = ({ onChange, value }) => {
    const initialItems = () => value.map((tech) => ({
        id: Date.now() * Math.random(),
        value: tech,
    }));

    const [items, setItems] = useState<TechItem[]>(initialItems());
    const [editItem, setEditItem] = useState<TechItem | null>(null);
    const [item, setItem] = useState('');
    const [error, setError] = useState<undefined | string>(undefined);

    const handleSaveItem = () => {
        if (item.trim() !== '') {
            addItem()
            setError(undefined);
        } else {
            setError('El campo no puede estar vacío');
        }
    };

    const handleSetEditItem = (item: TechItem) => {
        setItem(item.value);
        setEditItem(item);
    };

    const addItem = () => {
        if (editItem) {
            const newItems = items.map((itemObj) =>
                itemObj.id === editItem.id ?
                    { ...itemObj, value: item.trim() } : itemObj
            )

            setItems(newItems);
        } else {
            setItems([...items, { id: Date.now(), value: item.trim() }]);
        }

        setItem('');
        setEditItem(null);
    }

    const handlePaste = (e: React.ClipboardEvent<HTMLInputElement>) => {
        const pasteData = e.clipboardData.getData('text');

        if (pasteData.includes(',')) {
            e.preventDefault();

            const newTechs = pasteData
                .split(',')
                .map((tech) => tech.trim())
                .filter((tech) => tech !== '');

            const newItems = newTechs.map((tech) => ({
                id: Date.now() * Math.random(),
                value: tech,
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
                                handleSaveItem();
                            }
                        }}
                    />

                    <Button
                        className="flex-0"
                        variant="secondary"
                        type="button"
                        onClick={handleSaveItem}
                    >
                        {editItem ? (
                            <Pencil className='size-4' />
                        ) : (
                            <Plus className='size-4' />
                        )}
                    </Button>
                </div>

                <InputError message={error} />
            </div>

            {items.length > 0 && (
                <div className="flex flex-wrap gap-2">
                    {items.map((item) => (
                        <Item
                            variant="muted"
                            className="w-fit gap-1 overflow-hidden p-0"
                            key={item.id}
                            onDoubleClick={() => handleSetEditItem(item)}
                        >
                            <ItemMedia variant="icon" className="bg-accent p-2">
                                <Code className="size-6" />
                            </ItemMedia>
                            <ItemContent className="p-2">
                                <ItemTitle>{item.value}</ItemTitle>
                            </ItemContent>

                            <ItemActions className="p-2">
                                <Button
                                    className="flex-0"
                                    variant="destructive"
                                    type="button"
                                    onClick={() => handleRemoveItem(item.id)}
                                >
                                    <Trash className='size-4' />
                                </Button>
                            </ItemActions>
                        </Item>
                    ))}
                </div>
            )}
        </div>
    );
};

export default TechStackInput;
