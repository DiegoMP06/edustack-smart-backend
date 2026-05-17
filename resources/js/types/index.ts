export type PivotType<T = unknown, P = unknown> = T & {
    pivot: { id: number } & P;
};

export * from './auth';
export * from './classroom';
export * from './events';
export * from './forms';
export * from './media';
export * from './navigation';
export * from './ui';
export * from './admin'
