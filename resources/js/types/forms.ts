import type { FORM_RESPONSE_STATUS } from '@/consts/forms';

export type FormResponseStatus = keyof typeof FORM_RESPONSE_STATUS;
export type FormResponseStatusValue =
    (typeof FORM_RESPONSE_STATUS)[FormResponseStatus];
