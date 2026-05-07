// export const applyDateKeepingTime = (
//     currentDate: Date | undefined,
//     nextDate: Date,
// ): Date => {
//     const dateWithTime = new Date(nextDate);

//     if (currentDate) {
//         dateWithTime.setHours(
//             currentDate.getHours(),
//             currentDate.getMinutes(),
//             0,
//             0,
//         );
//     }

//     return dateWithTime;
// };

export const formatDatetimeToLocale = (
    dateString: string,
    locale: string = 'es-MX',
) =>
    new Date(dateString).toLocaleDateString(locale, {
        year: 'numeric',
        month: 'long',
        weekday: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    });

export function toDatetimeLocal(value: Date): string {
    const year = value.getFullYear();
    const month = `${value.getMonth() + 1}`.padStart(2, '0');
    const day = `${value.getDate()}`.padStart(2, '0');
    const hours = `${value.getHours()}`.padStart(2, '0');
    const minutes = `${value.getMinutes()}`.padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
}
