// services/MileageCodeService.js
export const MileageCodeService = {
    getMileageCodes() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { code: 'T060', description: 'Less than 60KM' },
                    { code: 'T100', description: 'Between 60 and 100KM' },
                    { code: 'T160', description: 'Between 100 and 160KM' },
                ]);
            }, 500); // Simulate an API delay
        });
    },
};
