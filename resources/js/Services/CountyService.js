// services/CountyService.js
export const CountyService = {
    getCounties() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { code: 'C001', description: 'Mombasa' },
                    { code: 'C002', description: 'Nairobi' },
                    { code: 'C003', description: 'Kiambu' },
                ]);
            }, 500); // Simulate an API delay
        });
    },
};
