import type { PAYMENT_STATUS } from '@/consts/payments';

export type PaymentStatus = keyof typeof PAYMENT_STATUS;
export type PaymentStatusValue = (typeof PAYMENT_STATUS)[PaymentStatus];
