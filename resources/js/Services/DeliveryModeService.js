// services/DeliveryModeService.js
export const DeliveryModeService = {
    getDeliveryModes() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { code: 'SELF', description: 'Self Delivery' },
                    { code: 'COMPANY', description: 'Company Collection' },

                ]);
            }, 500); // Simulate an API delay
        });
    },
};
