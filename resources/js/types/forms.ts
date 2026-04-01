import type {
    FORM_CONDITION_OPERATORS,
    FORM_LOGIC_ACTION_TYPES,
    FORM_LOGIC_OPERATORS,
    FORM_QUESTION_TYPES,
    FORM_RESPONSE_STATUS,
    FORM_RESULTS_VISIBILITY,
} from '@/consts/forms';
import type { UserData } from './auth';

export type FormResponseStatus = keyof typeof FORM_RESPONSE_STATUS;
export type FormResponseStatusValue =
    (typeof FORM_RESPONSE_STATUS)[FormResponseStatus];

export type FormResultsVisibility = keyof typeof FORM_RESULTS_VISIBILITY;
export type FormResultsVisibilityValue =
    (typeof FORM_RESULTS_VISIBILITY)[FormResultsVisibility];

export type FormLogicActionType = keyof typeof FORM_LOGIC_ACTION_TYPES;
export type FormLogicActionTypeValue =
    (typeof FORM_LOGIC_ACTION_TYPES)[FormLogicActionType];

export type FormConditionOperator = keyof typeof FORM_CONDITION_OPERATORS;
export type FormConditionOperatorValue =
    (typeof FORM_CONDITION_OPERATORS)[FormConditionOperator];

export type FormLogicOperator = keyof typeof FORM_LOGIC_OPERATORS;
export type FormLogicOperatorValue =
    (typeof FORM_LOGIC_OPERATORS)[FormLogicOperator];

export type FormQuestionType = keyof typeof FORM_QUESTION_TYPES;
export type FormQuestionTypeValue =
    (typeof FORM_QUESTION_TYPES)[FormQuestionType];

export type FormType = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    order: number;
};

export type FormQuestionOption = {
    id: number;
    text: string;
    value: string | null;
    image_url: string | null;
    order: number;
    is_row: boolean;
    correct_order: number | null;
    match_option_id: number | null;
    is_correct: boolean;
    feedback: string | null;
    form_question_id: number;
};

export type FormQuestion = {
    id: number;
    title: string;
    description: string | null;
    question_type: FormQuestionTypeValue | string;
    is_required: boolean;
    is_visible: boolean;
    order: number;
    settings: Record<string, unknown> | null;
    has_correct_answer: boolean;
    score: number;
    explanation: string | null;
    form_id: number;
    form_section_id: number | null;
    options?: FormQuestionOption[];
    section?: FormSection | null;
};

export type FormSection = {
    id: number;
    title: string;
    description: string | null;
    order: number;
    is_visible: boolean;
    form_id: number;
    questions?: FormQuestion[];
};

export type FormLogicCondition = {
    id: number;
    source_question_id: number;
    operator: FormLogicOperatorValue | string;
    comparison_value: unknown[] | Record<string, unknown> | null;
    comparison_option_id: number | null;
    form_logic_rule_id: number;
    sourceQuestion?: FormQuestion;
    source_question?: FormQuestion;
};

export type FormLogicRule = {
    id: number;
    name: string | null;
    action_type: FormLogicActionTypeValue | string;
    target_question_id: number | null;
    target_section_id: number | null;
    condition_operator: FormConditionOperatorValue | string;
    order: number;
    is_active: boolean;
    form_id: number;
    conditions?: FormLogicCondition[];
    targetQuestion?: FormQuestion | null;
    target_question?: FormQuestion | null;
    targetSection?: FormSection | null;
    target_section?: FormSection | null;
};

export type FormResponseAnswer = {
    id: number;
    form_response_id: number;
    form_question_id: number;
    text_answer: string | null;
    number_answer: number | null;
    date_answer: string | null;
    time_answer: string | null;
    datetime_answer: string | null;
    selected_option_ids: number[] | null;
    structured_answer: Record<string, unknown> | unknown[] | null;
    is_correct: boolean | null;
    score_awarded: number | null;
    feedback: string | null;
    was_skipped: boolean;
    question?: FormQuestion;
};

export type FormResponse = {
    id: number;
    form_id: number;
    user_id: number | null;
    respondent_email: string | null;
    attempt_number: number;
    status: FormResponseStatusValue | string;
    ip_address: string | null;
    user_agent: string | null;
    score: number | null;
    max_score: number | null;
    percentage: number | null;
    passed: boolean | null;
    graded_by: number | null;
    graded_at: string | null;
    started_at: string;
    submitted_at: string | null;
    user?: UserData | null;
    gradedBy?: UserData | null;
    graded_by_user?: UserData | null;
    answers?: FormResponseAnswer[];
};

export type Form = {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    form_type_id: number;
    is_published: boolean;
    is_active: boolean;
    requires_login: boolean;
    allow_multiple_responses: boolean;
    max_responses: number | null;
    collect_email: boolean;
    show_progress_bar: boolean;
    shuffle_sections: boolean;
    available_from: string | null;
    available_until: string | null;
    confirmation_message: string | null;
    redirect_url: string | null;
    is_quiz_mode: boolean;
    time_limit_minutes: number | null;
    max_attempts: number;
    passing_score: number | null;
    randomize_questions: boolean;
    randomize_options: boolean;
    show_results_to_respondent: FormResultsVisibilityValue | string;
    show_correct_answers: boolean;
    show_feedback_after: boolean;
    user_id: number;
    created_at: string;
    updated_at: string;
    type?: FormType;
    form_type?: FormType;
    sections?: FormSection[];
    form_sections?: FormSection[];
    questions?: FormQuestion[];
    form_questions?: FormQuestion[];
    logicRules?: FormLogicRule[];
    logic_rules?: FormLogicRule[];
    responses?: FormResponse[];
    form_responses?: FormResponse[];
};

export type FormPayload = {
    title: string;
    description: string;
    form_type_id: number;
    requires_login: boolean;
    allow_multiple_responses: boolean;
    max_responses: number | null;
    collect_email: boolean;
    show_progress_bar: boolean;
    shuffle_sections: boolean;
    available_from: string | null;
    available_until: string | null;
    confirmation_message: string | null;
    redirect_url: string | null;
    is_quiz_mode: boolean;
    time_limit_minutes: number | null;
    max_attempts: number;
    passing_score: number | null;
    randomize_questions: boolean;
    randomize_options: boolean;
    show_results_to_respondent: FormResultsVisibilityValue;
    show_correct_answers: boolean;
    show_feedback_after: boolean;
};
