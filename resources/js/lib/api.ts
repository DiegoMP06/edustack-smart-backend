const BASE_URL = import.meta.env.VITE_APP_URL ?? 'http://localhost:8000';
const API_PREFIX = '/api';

export class ApiRequestError extends Error {
    constructor(
        public readonly status: number,
        message: string,
        public readonly errors?: Record<string, string[]>,
    ) {
        super(message);
        this.name = 'ApiRequestError';
    }
}

export async function apiFetch<T>(
    path: string,
    params?: Record<
        string,
        string | number | boolean | Record<string, string | number | boolean>
    >,
    cacheInit: RequestInit = {},
): Promise<T> {
    const url = new URL(`${BASE_URL}${API_PREFIX}${path}`);

    if (params) {
        Object.entries(params).forEach(([key, value]) => {
            if (value === undefined || value === null || value === '') {
                return;
            }

            if (typeof value === 'object') {
                Object.entries(value).forEach(([nestedKey, nestedValue]) => {
                    if (
                        nestedValue === undefined ||
                        nestedValue === null ||
                        nestedValue === ''
                    ) {
                        return;
                    }

                    url.searchParams.set(
                        `${key}[${nestedKey}]`,
                        String(nestedValue),
                    );
                });

                return;
            }

            url.searchParams.set(key, String(value));
        });
    }

    const customHeaders = cacheInit.headers
        ? new Headers(cacheInit.headers)
        : new Headers();

    if (!customHeaders.has('Accept')) {
        customHeaders.set('Accept', 'application/json');
    }

    if (!customHeaders.has('X-Requested-With')) {
        customHeaders.set('X-Requested-With', 'XMLHttpRequest');
    }

    const csrfToken = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];

    if (csrfToken) {
        customHeaders.set('X-XSRF-TOKEN', decodeURIComponent(csrfToken));
    }

    const res = await fetch(url.toString(), {
        ...cacheInit,
        headers: customHeaders,
        credentials: 'include',
    });

    if (!res.ok) {
        const err = await res.json().catch(() => ({ message: res.statusText }));

        throw new ApiRequestError(res.status, err.message, err.errors);
    }

    return res.json() as Promise<T>;
}
