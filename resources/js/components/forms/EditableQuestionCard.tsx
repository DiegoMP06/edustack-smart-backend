import { router, useForm } from '@inertiajs/react';
import { Plus, Save, Trash2 } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { toast } from 'sonner';
import { Badge } from '@/components/ui/shadcn/badge';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/shadcn/select';
import { Switch } from '@/components/ui/shadcn/switch';
import { Textarea } from '@/components/ui/shadcn/textarea';
import { FORM_QUESTION_TYPES } from '@/consts/forms';
import options from '@/routes/forms/questions/options';
import type {
    FormQuestion,
    FormQuestionOption,
    FormQuestionTypeValue,
    FormSection,
} from '@/types/forms';

export type QuestionPayload = {
    title: string;
    description: string;
    question_type: FormQuestionTypeValue;
    is_required: boolean;
    is_visible: boolean;
    order: number;
    settings: null;
    has_correct_answer: boolean;
    score: number;
    explanation: string;
    form_section_id: number | null;
};

type OptionPayload = {
    text: string;
    value: string | null;
    image_url: string | null;
    order: number;
    is_row: boolean;
    correct_order: number | null;
    match_option_id: number | null;
    is_correct: boolean;
    feedback: string | null;
};

type EditableQuestionCardProps = {
    formId: number;
    sections: FormSection[];
    question: FormQuestion;
    onUpdate: (question: FormQuestion, payload: QuestionPayload) => void;
    onDelete: () => void;
};

const questionTypes = Object.values(FORM_QUESTION_TYPES);

export default function EditableQuestionCard({
    formId,
    sections,
    question,
    onUpdate,
    onDelete,
}: EditableQuestionCardProps) {
    const [payload, setPayload] = useState<QuestionPayload>({
        title: question.title,
        description: question.description ?? '',
        question_type: question.question_type as FormQuestionTypeValue,
        is_required: question.is_required,
        is_visible: question.is_visible,
        order: question.order,
        settings: null,
        has_correct_answer: question.has_correct_answer,
        score: Number(question.score),
        explanation: question.explanation ?? '',
        form_section_id: question.form_section_id,
    });

    const createOptionForm = useForm<OptionPayload>({
        text: '',
        value: null,
        image_url: null,
        order: question.options?.length ?? 0,
        is_row: false,
        correct_order: null,
        match_option_id: null,
        is_correct: false,
        feedback: null,
    });

    const createOption = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        createOptionForm.post(
            options.store({ form: formId, question: question.id }).url,
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                    createOptionForm.reset();
                    createOptionForm.setData(
                        'order',
                        question.options?.length ?? 0,
                    );
                },
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
            },
        );
    };

    const removeOption = (option: FormQuestionOption): void => {
        router.delete(
            options.destroy({
                form: formId,
                question: question.id,
                option: option.id,
            }).url,
            {
                preserveScroll: true,
                onSuccess: (page) =>
                    toast.success(page.props.message as string),
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
            },
        );
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle>{question.title}</CardTitle>
                <CardDescription className="flex flex-wrap items-center gap-2">
                    <Badge variant="outline">{question.question_type}</Badge>
                    <Badge
                        variant={question.is_required ? 'default' : 'secondary'}
                    >
                        {question.is_required ? 'Requerida' : 'Opcional'}
                    </Badge>
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

                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="grid gap-2">
                        <Label>Tipo</Label>
                        <Select
                            value={payload.question_type}
                            onValueChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    question_type:
                                        value as FormQuestionTypeValue,
                                }))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {questionTypes.map((type) => (
                                    <SelectItem key={type} value={type}>
                                        {type}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

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

                    <div className="grid gap-2">
                        <Label>Puntaje</Label>
                        <Input
                            type="number"
                            min={0}
                            step="0.01"
                            value={payload.score}
                            onChange={(event) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    score: Number(event.target.value || 0),
                                }))
                            }
                        />
                    </div>
                </div>

                <div className="grid gap-2">
                    <Label>Seccion</Label>
                    <Select
                        value={
                            payload.form_section_id
                                ? String(payload.form_section_id)
                                : 'none'
                        }
                        onValueChange={(value) =>
                            setPayload((previous) => ({
                                ...previous,
                                form_section_id:
                                    value === 'none' ? null : Number(value),
                            }))
                        }
                    >
                        <SelectTrigger>
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="none">Sin seccion</SelectItem>
                            {sections.map((section) => (
                                <SelectItem
                                    key={section.id}
                                    value={String(section.id)}
                                >
                                    {section.title}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                </div>

                <div className="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={payload.is_required}
                            onCheckedChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    is_required: value,
                                }))
                            }
                        />
                        <Label>Requerida</Label>
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
                    <div className="flex items-center gap-2 rounded-md border p-3">
                        <Switch
                            checked={payload.has_correct_answer}
                            onCheckedChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    has_correct_answer: value,
                                }))
                            }
                        />
                        <Label>Calificable</Label>
                    </div>
                </div>

                <div className="flex flex-wrap gap-2">
                    <Button onClick={() => onUpdate(question, payload)}>
                        <Save />
                        Guardar
                    </Button>
                    <Button variant="destructive" onClick={onDelete}>
                        <Trash2 />
                        Eliminar
                    </Button>
                </div>

                <div className="rounded-md border p-4">
                    <h4 className="mb-3 text-sm font-semibold">
                        Opciones ({question.options?.length ?? 0})
                    </h4>

                    {question.options && question.options.length > 0 ? (
                        <div className="mb-4 grid gap-2">
                            {question.options.map((option) => (
                                <div
                                    key={option.id}
                                    className="flex items-center justify-between gap-3 rounded-md border px-3 py-2"
                                >
                                    <div>
                                        <p className="text-sm font-medium">
                                            {option.text}
                                        </p>
                                        <p className="text-xs text-muted-foreground">
                                            Orden: {option.order}
                                        </p>
                                    </div>
                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        onClick={() => removeOption(option)}
                                    >
                                        <Trash2 />
                                    </Button>
                                </div>
                            ))}
                        </div>
                    ) : null}

                    <form className="grid gap-2" onSubmit={createOption}>
                        <Label>Nueva opcion</Label>
                        <Input
                            value={createOptionForm.data.text}
                            onChange={(event) =>
                                createOptionForm.setData(
                                    'text',
                                    event.target.value,
                                )
                            }
                            placeholder="Texto de la opcion"
                        />
                        <Button
                            size="sm"
                            type="submit"
                            disabled={createOptionForm.processing}
                        >
                            <Plus />
                            Agregar opcion
                        </Button>
                    </form>
                </div>
            </CardContent>
        </Card>
    );
}
