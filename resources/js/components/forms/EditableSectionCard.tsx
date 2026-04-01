import { Save, Trash2 } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/shadcn/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/shadcn/card';
import { Input } from '@/components/ui/shadcn/input';
import { Label } from '@/components/ui/shadcn/label';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import type { FormSection } from '@/types/forms';

export type SectionPayload = {
    title: string;
    description: string;
    order: number;
    is_visible: boolean;
};

type EditableSectionCardProps = {
    section: FormSection;
    onUpdate: (section: FormSection, payload: SectionPayload) => void;
    onDelete: () => void;
};

export default function EditableSectionCard({
    section,
    onUpdate,
    onDelete,
}: EditableSectionCardProps) {
    const [payload, setPayload] = useState<SectionPayload>({
        title: section.title,
        description: section.description ?? '',
        order: section.order,
        is_visible: section.is_visible,
    });

    return (
        <Card>
            <CardHeader>
                <CardTitle>{section.title}</CardTitle>
                <CardDescription>
                    Preguntas en la seccion: {section.questions?.length ?? 0}
                </CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
                <div className="grid gap-2">
                    <Label>Titulo</Label>
                    <Input
                        value={payload.title}
                        onChange={(event) =>
                            setPayload((previous) => ({
                                ...previous,
                                title: event.target.value,
                            }))
                        }
                    />
                </div>

                <div className="grid gap-2">
                    <Label>Descripcion</Label>
                    <Textarea
                        value={payload.description}
                        onChange={(event) =>
                            setPayload((previous) => ({
                                ...previous,
                                description: event.target.value,
                            }))
                        }
                    />
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div className="grid gap-2">
                        <Label>Orden</Label>
                        <Input
                            type="number"
                            min={0}
                            value={payload.order}
                            onChange={(event) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    order: Number(event.target.value || 0),
                                }))
                            }
                        />
                    </div>

                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={payload.is_visible}
                            onCheckedChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    is_visible: value,
                                }))
                            }
                        />
                        <Label>Visible</Label>
                    </div>
                </div>

                <div className="flex flex-wrap gap-2">
                    <Button onClick={() => onUpdate(section, payload)}>
                        <Save />
                        Guardar
                    </Button>
                    <Button variant="destructive" onClick={onDelete}>
                        <Trash2 />
                        Eliminar
                    </Button>
                </div>
            </CardContent>
        </Card>
    );
}
