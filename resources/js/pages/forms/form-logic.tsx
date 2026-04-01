import { Head, router, useForm } from '@inertiajs/react';
import { ChevronLeft, Plus } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { toast } from 'sonner';
import EditableRuleCard from '@/components/forms/EditableRuleCard';
import type { RulePayload } from '@/components/forms/EditableRuleCard';
import ConfirmDialog from '@/components/ui/app/confirm-dialog';
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
import {
    FORM_CONDITION_OPERATORS,
    FORM_LOGIC_ACTION_TYPES,
} from '@/consts/forms';
import AppLayout from '@/layouts/app-layout';
import forms from '@/routes/forms';
import logicRules from '@/routes/forms/logic-rules';
import type { BreadcrumbItem } from '@/types';
import type {
    Form,
    FormConditionOperatorValue,
    FormLogicActionTypeValue,
    FormLogicRule,
    FormQuestion,
    FormSection,
} from '@/types/forms';

type FormLogicProps = {
    form: Form;
};

const breadcrumbs = (form: Form): BreadcrumbItem[] => [
    { title: 'Formularios', href: forms.index().url },
    { title: form.title, href: forms.show(form.id).url },
    { title: 'Logica', href: logicRules.index(form.id).url },
];

const getRules = (form: Form): FormLogicRule[] =>
    form.logicRules ?? form.logic_rules ?? [];
const getQuestions = (form: Form): FormQuestion[] =>
    form.questions ?? form.form_questions ?? [];
const getSections = (form: Form): FormSection[] =>
    form.sections ?? form.form_sections ?? [];

export default function FormLogic({ form }: FormLogicProps) {
    const rulesList = getRules(form);
    const questionsList = getQuestions(form);
    const sectionsList = getSections(form);
    const [ruleToDelete, setRuleToDelete] = useState<FormLogicRule | null>(
        null,
    );

    const createForm = useForm<RulePayload>({
        name: '',
        action_type: FORM_LOGIC_ACTION_TYPES.show_question,
        target_question_id: null,
        target_section_id: null,
        condition_operator: FORM_CONDITION_OPERATORS.and,
        order: rulesList.length,
        is_active: true,
    });

    const createRule = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        createForm.post(logicRules.store(form.id).url, {
            preserveScroll: true,
            onSuccess: (page) => {
                toast.success(
                    (page.props.message as string) ??
                        'Regla creada correctamente.',
                );
                createForm.reset();
                createForm.setData(
                    'action_type',
                    FORM_LOGIC_ACTION_TYPES.show_question,
                );
                createForm.setData(
                    'condition_operator',
                    FORM_CONDITION_OPERATORS.and,
                );
                createForm.setData('is_active', true);
            },
            onError: (errors) => {
                Object.values(errors).forEach((value) =>
                    toast.error(value as string),
                );
            },
        });
    };

    const updateRule = (rule: FormLogicRule, payload: RulePayload): void => {
        router.patch(
            logicRules.update({ form: form.id, rule: rule.id }).url,
            payload,
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

    const deleteRule = (): void => {
        if (!ruleToDelete) {
            return;
        }

        router.delete(
            logicRules.destroy({ form: form.id, rule: ruleToDelete.id }).url,
            {
                preserveScroll: true,
                onSuccess: (page) =>
                    toast.success(page.props.message as string),
                onError: (errors) => {
                    Object.values(errors).forEach((value) =>
                        toast.error(value as string),
                    );
                },
                onFinish: () => setRuleToDelete(null),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(form)}>
            <Head title={`Logica de ${form.title}`} />

            <div className="mb-6">
                <Button
                    variant="outline"
                    onClick={() => router.visit(forms.edit(form.id))}
                >
                    <ChevronLeft />
                    Volver
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Nueva regla</CardTitle>
                        <CardDescription>
                            Define acciones condicionales para el formulario.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form className="grid gap-4" onSubmit={createRule}>
                            <div className="grid gap-2">
                                <Label>Nombre</Label>
                                <Input
                                    value={createForm.data.name}
                                    onChange={(event) =>
                                        createForm.setData(
                                            'name',
                                            event.target.value,
                                        )
                                    }
                                />
                            </div>

                            <div className="grid gap-2">
                                <Label>Tipo de accion</Label>
                                <Select
                                    value={createForm.data.action_type}
                                    onValueChange={(value) =>
                                        createForm.setData(
                                            'action_type',
                                            value as FormLogicActionTypeValue,
                                        )
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {Object.values(
                                            FORM_LOGIC_ACTION_TYPES,
                                        ).map((actionType) => (
                                            <SelectItem
                                                key={actionType}
                                                value={actionType}
                                            >
                                                {actionType}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label>Pregunta destino</Label>
                                    <Select
                                        value={
                                            createForm.data.target_question_id
                                                ? String(
                                                      createForm.data
                                                          .target_question_id,
                                                  )
                                                : 'none'
                                        }
                                        onValueChange={(value) =>
                                            createForm.setData(
                                                'target_question_id',
                                                value === 'none'
                                                    ? null
                                                    : Number(value),
                                            )
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="none">
                                                Sin pregunta
                                            </SelectItem>
                                            {questionsList.map((question) => (
                                                <SelectItem
                                                    key={question.id}
                                                    value={String(question.id)}
                                                >
                                                    {question.title}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                </div>

                                <div className="grid gap-2">
                                    <Label>Seccion destino</Label>
                                    <Select
                                        value={
                                            createForm.data.target_section_id
                                                ? String(
                                                      createForm.data
                                                          .target_section_id,
                                                  )
                                                : 'none'
                                        }
                                        onValueChange={(value) =>
                                            createForm.setData(
                                                'target_section_id',
                                                value === 'none'
                                                    ? null
                                                    : Number(value),
                                            )
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="none">
                                                Sin seccion
                                            </SelectItem>
                                            {sectionsList.map((section) => (
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
                            </div>

                            <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label>Operador</Label>
                                    <Select
                                        value={
                                            createForm.data.condition_operator
                                        }
                                        onValueChange={(value) =>
                                            createForm.setData(
                                                'condition_operator',
                                                value as FormConditionOperatorValue,
                                            )
                                        }
                                    >
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {Object.values(
                                                FORM_CONDITION_OPERATORS,
                                            ).map((operator) => (
                                                <SelectItem
                                                    key={operator}
                                                    value={operator}
                                                >
                                                    {operator.toUpperCase()}
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
                                        value={createForm.data.order}
                                        onChange={(event) =>
                                            createForm.setData(
                                                'order',
                                                Number(event.target.value || 0),
                                            )
                                        }
                                    />
                                </div>
                            </div>

                            <div className="flex items-center gap-2 rounded-md border p-3">
                                <Switch
                                    checked={createForm.data.is_active}
                                    onCheckedChange={(value) =>
                                        createForm.setData('is_active', value)
                                    }
                                />
                                <Label>Regla activa</Label>
                            </div>

                            <Button
                                type="submit"
                                disabled={createForm.processing}
                            >
                                <Plus />
                                Agregar Regla
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <div className="grid gap-4 lg:col-span-2">
                    {rulesList.length > 0 ? (
                        rulesList.map((rule) => (
                            <EditableRuleCard
                                key={rule.id}
                                formId={form.id}
                                rule={rule}
                                questions={questionsList}
                                sections={sectionsList}
                                onUpdate={updateRule}
                                onDelete={() => setRuleToDelete(rule)}
                            />
                        ))
                    ) : (
                        <p className="py-12 text-center text-accent-foreground">
                            No hay reglas registradas.
                        </p>
                    )}
                </div>
            </div>

            <ConfirmDialog
                open={Boolean(ruleToDelete)}
                onOpenChange={(open) => {
                    if (!open) {
                        setRuleToDelete(null);
                    }
                }}
                onConfirm={deleteRule}
                title="Eliminar regla"
                description="Esta accion eliminara la regla y sus condiciones."
                confirmLabel="Si, eliminar"
            />
        </AppLayout>
    );
}
