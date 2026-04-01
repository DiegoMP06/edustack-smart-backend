import { router, useForm } from '@inertiajs/react';
import { Plus, Save, Trash2 } from 'lucide-react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { toast } from 'sonner';
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
    FORM_LOGIC_OPERATORS,
} from '@/consts/forms';
import conditions from '@/routes/forms/logic-rules/conditions';
import type {
    FormConditionOperatorValue,
    FormLogicActionTypeValue,
    FormLogicCondition,
    FormLogicOperatorValue,
    FormLogicRule,
    FormQuestion,
    FormSection,
} from '@/types/forms';

export type RulePayload = {
    name: string;
    action_type: FormLogicActionTypeValue;
    target_question_id: number | null;
    target_section_id: number | null;
    condition_operator: FormConditionOperatorValue;
    order: number;
    is_active: boolean;
};

type ConditionPayload = {
    source_question_id: number;
    operator: FormLogicOperatorValue;
    comparison_value: string | null;
    comparison_option_id: number | null;
};

type EditableRuleCardProps = {
    formId: number;
    rule: FormLogicRule;
    questions: FormQuestion[];
    sections: FormSection[];
    onUpdate: (rule: FormLogicRule, payload: RulePayload) => void;
    onDelete: () => void;
};

export default function EditableRuleCard({
    formId,
    rule,
    questions,
    sections,
    onUpdate,
    onDelete,
}: EditableRuleCardProps) {
    const [payload, setPayload] = useState<RulePayload>({
        name: rule.name ?? '',
        action_type: rule.action_type as FormLogicActionTypeValue,
        target_question_id: rule.target_question_id,
        target_section_id: rule.target_section_id,
        condition_operator:
            rule.condition_operator as FormConditionOperatorValue,
        order: rule.order,
        is_active: rule.is_active,
    });

    const createConditionForm = useForm<ConditionPayload>({
        source_question_id: questions[0]?.id ?? 0,
        operator: FORM_LOGIC_OPERATORS.equals,
        comparison_value: null,
        comparison_option_id: null,
    });

    const ruleConditions = rule.conditions ?? [];

    const createCondition = (event: FormEvent<HTMLFormElement>): void => {
        event.preventDefault();

        createConditionForm.post(
            conditions.store({ form: formId, rule: rule.id }).url,
            {
                preserveScroll: true,
                onSuccess: (page) => {
                    toast.success(page.props.message as string);
                    createConditionForm.reset();
                    createConditionForm.setData(
                        'source_question_id',
                        questions[0]?.id ?? 0,
                    );
                    createConditionForm.setData(
                        'operator',
                        FORM_LOGIC_OPERATORS.equals,
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

    const deleteCondition = (condition: FormLogicCondition): void => {
        router.delete(
            conditions.destroy({
                form: formId,
                rule: rule.id,
                condition: condition.id,
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
                <CardTitle>{rule.name || `Regla #${rule.id}`}</CardTitle>
                <CardDescription>
                    Condiciones: {ruleConditions.length}
                </CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4">
                <div className="grid gap-2">
                    <Label>Nombre</Label>
                    <Input
                        value={payload.name}
                        onChange={(event) =>
                            setPayload((previous) => ({
                                ...previous,
                                name: event.target.value,
                            }))
                        }
                    />
                </div>

                <div className="grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div className="grid gap-2">
                        <Label>Tipo de accion</Label>
                        <Select
                            value={payload.action_type}
                            onValueChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    action_type:
                                        value as FormLogicActionTypeValue,
                                }))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {Object.values(FORM_LOGIC_ACTION_TYPES).map(
                                    (actionType) => (
                                        <SelectItem
                                            key={actionType}
                                            value={actionType}
                                        >
                                            {actionType}
                                        </SelectItem>
                                    ),
                                )}
                            </SelectContent>
                        </Select>
                    </div>

                    <div className="grid gap-2">
                        <Label>Operador de condiciones</Label>
                        <Select
                            value={payload.condition_operator}
                            onValueChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    condition_operator:
                                        value as FormConditionOperatorValue,
                                }))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                {Object.values(FORM_CONDITION_OPERATORS).map(
                                    (operator) => (
                                        <SelectItem
                                            key={operator}
                                            value={operator}
                                        >
                                            {operator.toUpperCase()}
                                        </SelectItem>
                                    ),
                                )}
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div className="grid gap-2">
                        <Label>Pregunta destino</Label>
                        <Select
                            value={
                                payload.target_question_id
                                    ? String(payload.target_question_id)
                                    : 'none'
                            }
                            onValueChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    target_question_id:
                                        value === 'none' ? null : Number(value),
                                }))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="none">
                                    Sin pregunta
                                </SelectItem>
                                {questions.map((question) => (
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
                                payload.target_section_id
                                    ? String(payload.target_section_id)
                                    : 'none'
                            }
                            onValueChange={(value) =>
                                setPayload((previous) => ({
                                    ...previous,
                                    target_section_id:
                                        value === 'none' ? null : Number(value),
                                }))
                            }
                        >
                            <SelectTrigger>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="none">
                                    Sin seccion
                                </SelectItem>
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
                </div>

                <div className="flex items-center gap-2 rounded-md border p-3">
                    <Switch
                        checked={payload.is_active}
                        onCheckedChange={(value) =>
                            setPayload((previous) => ({
                                ...previous,
                                is_active: value,
                            }))
                        }
                    />
                    <Label>Regla activa</Label>
                </div>

                <div className="flex flex-wrap gap-2">
                    <Button onClick={() => onUpdate(rule, payload)}>
                        <Save />
                        Guardar
                    </Button>
                    <Button variant="destructive" onClick={onDelete}>
                        <Trash2 />
                        Eliminar
                    </Button>
                </div>

                <div className="rounded-md border p-4">
                    <h4 className="mb-3 text-sm font-semibold">Condiciones</h4>

                    {ruleConditions.length > 0 ? (
                        <div className="mb-4 grid gap-2">
                            {ruleConditions.map((condition) => (
                                <div
                                    key={condition.id}
                                    className="flex items-center justify-between gap-3 rounded-md border px-3 py-2"
                                >
                                    <div>
                                        <p className="text-sm font-medium">
                                            {condition.operator}
                                        </p>
                                        <p className="text-xs text-muted-foreground">
                                            Pregunta fuente:{' '}
                                            {condition.source_question_id}
                                        </p>
                                    </div>
                                    <Button
                                        size="sm"
                                        variant="destructive"
                                        onClick={() =>
                                            deleteCondition(condition)
                                        }
                                    >
                                        <Trash2 />
                                    </Button>
                                </div>
                            ))}
                        </div>
                    ) : null}

                    <form className="grid gap-2" onSubmit={createCondition}>
                        <div className="grid grid-cols-1 gap-2 md:grid-cols-2">
                            <Select
                                value={String(
                                    createConditionForm.data.source_question_id,
                                )}
                                onValueChange={(value) =>
                                    createConditionForm.setData(
                                        'source_question_id',
                                        Number(value),
                                    )
                                }
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Pregunta fuente" />
                                </SelectTrigger>
                                <SelectContent>
                                    {questions.map((question) => (
                                        <SelectItem
                                            key={question.id}
                                            value={String(question.id)}
                                        >
                                            {question.title}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            <Select
                                value={createConditionForm.data.operator}
                                onValueChange={(value) =>
                                    createConditionForm.setData(
                                        'operator',
                                        value as FormLogicOperatorValue,
                                    )
                                }
                            >
                                <SelectTrigger>
                                    <SelectValue placeholder="Operador" />
                                </SelectTrigger>
                                <SelectContent>
                                    {Object.values(FORM_LOGIC_OPERATORS).map(
                                        (operator) => (
                                            <SelectItem
                                                key={operator}
                                                value={operator}
                                            >
                                                {operator}
                                            </SelectItem>
                                        ),
                                    )}
                                </SelectContent>
                            </Select>
                        </div>

                        <Input
                            placeholder="Valor de comparacion (texto)"
                            value={
                                createConditionForm.data.comparison_value ?? ''
                            }
                            onChange={(event) =>
                                createConditionForm.setData(
                                    'comparison_value',
                                    event.target.value || null,
                                )
                            }
                        />

                        <Button
                            type="submit"
                            size="sm"
                            disabled={createConditionForm.processing}
                        >
                            <Plus />
                            Agregar condicion
                        </Button>
                    </form>
                </div>
            </CardContent>
        </Card>
    );
}
