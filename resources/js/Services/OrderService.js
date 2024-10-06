// services/OrderService.js
export const OrderService = {
    getOrdersMini() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        'Order No.': 'O1001',
                        'Vendor No.': 'V1001',
                        'Vendor Name': 'Vendor 1',
                        'Posting Date': '2024-10-01',
                        'Order Quantity': 100,
                        'Received Quantity': 50,
                    },
                    {
                        'Order No.': 'O1002',
                        'Vendor No.': 'V1002',
                        'Vendor Name': 'Vendor 2',
                        'Posting Date': '2024-10-02',
                        'Order Quantity': 200,
                        'Received Quantity': 200,
                    },
                    {
                        'Order No.': 'O1003',
                        'Vendor No.': 'V1003',
                        'Vendor Name': 'Vendor 3',
                        'Posting Date': '2024-10-03',
                        'Order Quantity': 150,
                        'Received Quantity': 100,
                    },
                    {
                        'Order No.': 'O1004',
                        'Vendor No.': 'V1004',
                        'Vendor Name': 'Vendor 4',
                        'Posting Date': '2024-10-04',
                        'Order Quantity': 300,
                        'Received Quantity': 300,
                    },
                ]);
            }, 1000); // Simulate a delay of 1 second
        });
    },
};
