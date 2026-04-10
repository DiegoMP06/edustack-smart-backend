import { apiFetch } from '@/lib/api';

type UsersAPIParams = {
    search: string;
    page: number;
};

export default {
    getUsers({ search, page }: Pick<UsersAPIParams, 'search' | 'page'>) {
        return apiFetch('/users', { filter: { search }, page });
    },
};
