import axios from 'axios';

export const ReceiptService = {
    getReceiptsMini() {
        return axios.get('/purchaseReceipts')
            .then(response => {
                return response.data; // Return the orders from the API
            })
            .catch(error => {
                console.error('There was an error fetching the orders:', error);
            });
    },

    addOrder(receipt,receiptLines) {

        return axios.post('/purchaseReceipts', {receipt:receipt,
                                              receipt_lines:receiptLines
                                        });
            // .then(response => {
            //     return response.data;})
            // .catch(error => {
            //     console.error('There was an error adding the order:', error);
            // });
    },
};

export const ReceiptNumberService = {
    getNewOrderNumber() {
        return axios.get('/new-receipt-number')
            .then(response => {
                return response.data.receiptNumber;
            })
            .catch(error => {
                console.error('There was an error generating a new order number:', error);
            });
    },
};
