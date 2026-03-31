export const formatTime = (date?: Date): string => {
    if (!date) {
        return '00:00';
    }

    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${hours}:${minutes}`;
};
export const applyTimeToDate = (
    date: Date | undefined,
    timeValue: string,
): Date => {
    const [hours, minutes] = timeValue.split(':').map(Number);
    const nextDate = date ? new Date(date) : new Date();

    nextDate.setHours(hours || 0, minutes || 0, 0, 0);

    return nextDate;
};

export const applyDateKeepingTime = (
    currentDate: Date | undefined,
    nextDate: Date,
): Date => {
    const dateWithTime = new Date(nextDate);

    if (currentDate) {
        dateWithTime.setHours(
            currentDate.getHours(),
            currentDate.getMinutes(),
            0,
            0,
        );
    }

    return dateWithTime;
};
