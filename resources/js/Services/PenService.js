export const PenService = {
    getPens() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { pen_no: 'PEN001', description: 'PEN001' },
                    { pen_no: 'PEN002', description: 'PEN002' },
                    { pen_no: 'PEN003', description: 'PEN003' },

                ]);
            }, 1000); // Simulate a delay
        });
    },
};
