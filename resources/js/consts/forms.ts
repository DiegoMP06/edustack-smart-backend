export const FORM_RESPONSE_STATUS = {
    'in-progress': 'in-progress',
    submitted: 'submitted',
    graded: 'graded',
} as const;

export const FORM_RESULTS_VISIBILITY = {
    immediately: 'immediately',
    after_close: 'after_close',
    never: 'never',
} as const;

export const FORM_LOGIC_ACTION_TYPES = {
    show_question: 'show_question',
    hide_question: 'hide_question',
    require_question: 'require_question',
    skip_question: 'skip_question',
    jump_to_section: 'jump_to_section',
    end_form: 'end_form',
} as const;

export const FORM_CONDITION_OPERATORS = {
    and: 'and',
    or: 'or',
} as const;

export const FORM_LOGIC_OPERATORS = {
    equals: 'equals',
    not_equals: 'not_equals',
    contains: 'contains',
    not_contains: 'not_contains',
    starts_with: 'starts_with',
    ends_with: 'ends_with',
    greater_than: 'greater_than',
    greater_or_equal: 'greater_or_equal',
    less_than: 'less_than',
    less_or_equal: 'less_or_equal',
    is_answered: 'is_answered',
    is_empty: 'is_empty',
    includes_option: 'includes_option',
    excludes_option: 'excludes_option',
} as const;

export const FORM_QUESTION_TYPES = {
    short_text: 'short_text',
    long_text: 'long_text',
    email: 'email',
    phone: 'phone',
    url: 'url',
    number: 'number',
    single_choice: 'single_choice',
    multiple_choice: 'multiple_choice',
    dropdown: 'dropdown',
    yes_no: 'yes_no',
    image_choice: 'image_choice',
    linear_scale: 'linear_scale',
    rating: 'rating',
    nps: 'nps',
    likert_scale: 'likert_scale',
    semantic_diff: 'semantic_diff',
    matrix: 'matrix',
    checkbox_grid: 'checkbox_grid',
    ranking: 'ranking',
    date: 'date',
    time: 'time',
    datetime: 'datetime',
    fill_in_blank: 'fill_in_blank',
    matching: 'matching',
    ordering: 'ordering',
    code: 'code',
    file_upload: 'file_upload',
    signature: 'signature',
    section_break: 'section_break',
    statement: 'statement',
} as const;
