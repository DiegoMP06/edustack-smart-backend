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

export function toDateLocal(value: Date): string {
    const year = value.getFullYear();
    const month = `${value.getMonth() + 1}`.padStart(2, '0');
    const day = `${value.getDate() + 1}`.padStart(2, '0');

    return `${year}-${month}-${day}`;
}
