import axios from 'axios';

export const ReceiptService = {
    getReceipts() {
        return axios.get('/purchaseReceipts')
            .then(response => {
                return response.data; // Return the orders from the API
            })
            .catch(error => {
                console.error('There was an error fetching the orders:', error);
            });
    },

    addReceipt(receipt,receipt_lines) {

        return axios.post('/purchaseReceipts', {receipt,receipt_lines})
            .then(response => {
                return response.data;})
            .catch(error => {
                console.error('There was an error adding the order:', error);
            });
    },
};

export const ReceiptNumberService = {
    getNewReceiptNumber() {
        return axios.get('/new-receipt-number')
            .then(response => {
                return response.data.receiptNumber;
            })
            .catch(error => {
                console.error('There was an error generating a new receipt number:', error);
            });
    },
};
