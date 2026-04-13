import { Link2, Pencil, Plus, Trash } from "lucide-react";
import { useEffect, useState } from "react";
import type { SubmitHandler } from "react-hook-form";
import { useForm } from "react-hook-form";
import InputError from "@/components/ui/app/input-error";
import { Button } from "@/components/ui/shadcn/button";
import { Input } from "@/components/ui/shadcn/input";
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle } from "@/components/ui/shadcn/item";
import type { Speaker } from "@/types"

type SocialInputProps = {
    onChange: (value: Speaker['social']) => void;
    value: Speaker['social'];
}

type SocialItem = {
    id: number;
    name: string;
    url: string;
}

type SocialForm = Pick<SocialItem, 'name' | 'url'>

export default function SocialInput({ onChange, value }: SocialInputProps) {
    const initialItems = () => value.map((social) => ({
        ...social,
        id: Date.now() * Math.random(),
    }))

    const [items, setItems] = useState<SocialItem[]>(initialItems());
    const [editItem, setEditItem] = useState<SocialItem | null>(null);
    const { register, handleSubmit, formState: { errors }, setValue } = useForm({
        defaultValues: {
            name: '',
            url: ''
        }
    })

    console.log(items)

    const handleSaveItem: SubmitHandler<SocialForm> = (data) => {
        if (editItem) {
            const newItems = items.map((item) =>
                item.id === editItem.id ?
                    { ...data, id: item.id } : item
            )

            setItems(newItems);
        } else {
            setItems([...items, { id: Date.now(), ...data }]);
        }

        setValue('name', '');
        setValue('url', '');
        setEditItem(null);
    };


    const handleSetEditItem = (item: SocialItem) => {
        setValue('name', item.name);
        setValue('url', item.url);
        setEditItem(item);
    };


    const handleRemoveItem = (id: number) => {
        setItems(items.filter((item) => item.id !== id));
    };

    useEffect(() => {
        onChange(items.map(({ name, url }) => ({ name, url })));
    }, [items, onChange]);

    return (
        <div className="grid grid-cols-1 gap-4">
            <div className="grid gap-2">
                <div className="flex gap-2 items-center">
                    <div className="flex flex-1 flex-col md:flex-row gap-2">
                        <div className="flex-1 grid gap-1">
                            <Input
                                placeholder="Nombre de la red social"
                                className="flex-1"
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        handleSubmit(handleSaveItem)()
                                    }
                                }}
                                {...register('name', {
                                    required: 'El campo es requerido',
                                })}
                            />

                            <InputError message={errors.name?.message} />
                        </div>

                        <div className="flex-1 grid gap-1">
                            <Input
                                placeholder="URL de la red social"
                                className="flex-1"
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        handleSubmit(handleSaveItem)()
                                    }
                                }}
                                {...register('url', {
                                    required: 'El enlace es requerido',
                                    pattern: {
                                        value: /^(https?:\/\/)?(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/,
                                        message: 'Ingrese una URL válida',
                                    },
                                })}
                            />

                            <InputError message={errors.url?.message} />
                        </div>

                    </div>

                    <Button
                        className="flex-0"
                        variant="secondary"
                        type="button"
                        onClick={handleSubmit(handleSaveItem)}
                    >
                        {editItem ? (
                            <Pencil className='size-4' />
                        ) : (
                            <Plus className='size-4' />
                        )}
                    </Button>
                </div>

            </div>

            {items.length > 0 && (
                <div className="flex flex-col gap-2">
                    {items.map((item) => (
                        <Item
                            variant="muted"
                            className="w-full gap-1 p-0"
                            key={item.id}
                            onDoubleClick={() => handleSetEditItem(item)}
                        >
                            <ItemMedia variant="icon" className="bg-accent p-2 rounded-full">
                                <Link2 className="size-4" />
                            </ItemMedia>
                            <ItemContent className="p-2">
                                <ItemTitle>{item.name}</ItemTitle>
                                <ItemDescription className="text-xs">{item.url}</ItemDescription>
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
    )
}
