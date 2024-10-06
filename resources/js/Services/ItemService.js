// services/ItemService.js
export const ItemService = {
    getItems() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    { item_no: 'G0101', description: 'Pig Livestock' },
                    { item_no: 'G0102', description: 'Sow Livestock' },
                    { item_no: 'G0103', description: 'Suckling Livestock' },

                ]);
            }, 1000); // Simulate a delay
        });
    },
};
