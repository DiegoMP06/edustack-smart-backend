export const formatDateToLocale = (
    dateString: string,
    locale: string = 'es-MX',
) =>
    new Date(dateString).toLocaleDateString(locale, {
        year: 'numeric',
        month: 'long',
        weekday: 'long',
        day: 'numeric',
    });

export const compareDates = (date1: Date, date2: Date) => {
    return (
        date1.getFullYear() === date2.getFullYear() &&
        date1.getMonth() === date2.getMonth() &&
        date1.getDate() === date2.getDate()
    );
};

export function toDateLocal(value: Date | string | undefined): string {
    if (!value) {
        return '';
    }

    const date = value instanceof Date ? value : new Date(value);

    if (isNaN(date.getTime())) {
        return '';
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

export const formatDateToServer = (date: Date): string => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
};

export const stringToDate = (val: string | null | undefined) => {
    if (!val) {
        return new Date();
    }

    const [y, m, d] = val.split('-').map(Number);

    return new Date(y, m - 1, d);
};
