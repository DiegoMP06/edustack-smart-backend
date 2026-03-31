export type PivotType<T = unknown, P = unknown> = T & {
    pivot: { id: number } & P;
};

export * from './auth';
export * from './blog';
export * from './events';
export * from './media';
export * from './navigation';
export * from './projects';
export * from './ui';
