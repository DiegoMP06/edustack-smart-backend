export const formatCurrency = (currency: number) => {
    return Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(currency);
};
