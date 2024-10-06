// services/ModeOfPaymentService.js
export const ModeOfPaymentService = {
    getModesOfPayment() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { code: 'Credit', description: 'Credit' },
                    { code: 'Cash', description: 'Cash' },
                    { code: 'Cheque', description: 'Cheque' },
                    { code: 'EFT', description: 'EFT' },
                    { code: 'RTGS', description: 'RTGS' },
                ]);
            }, 500); // Simulate an API delay
        });
    },
};
