import axios from 'axios';

export const OrderService = {
    getOrders() {
        return axios.get('/purchaseOrders')
            .then(response => {
                return response.data; // Return the orders from the API
            })
            .catch(error => {
                console.error('There was an error fetching the orders:', error);
            });
    },

    addOrder(order,orderLines) {

        return axios.post('/purchaseOrders', {order:order,
                                              order_lines:orderLines
                                        });
            // .then(response => {
            //     return response.data;})
            // .catch(error => {
            //     console.error('There was an error adding the order:', error);
            // });
    },

     getPendingOrders() {
      return axios.get('/purchaseOrders') // Replace with your actual API endpoint
      .then(response => {
        // Filter the orders with 'Pending' status
        return response.data.filter(order => order.status === 'Pending');
      })
      .catch(error => {
        console.error('Error fetching orders:', error);
        throw error;
      });
  },

    // updateOrder(orderId, updatedOrder) {
    //     return axios.put(`/purchaseOrders/${orderId}`, updatedOrder)
    //         .then(response => {
    //             return response.data;
    //         })
    //         .catch(error => {
    //             console.error('There was an error updating the order:', error);
    //         });
    // },

    // deleteOrder(orderId) {
    //     return axios.delete(`/purchaseOrders/${orderId}`)
    //         .then(response => {
    //             return response.data;
    //         })
    //         .catch(error => {
    //             console.error('There was an error deleting the order:', error);
    //         });
    // },

    calculateOrderQuantity(orderLines) {
        return orderLines.reduce((total, line) => total + parseInt(line.order_qty), 0);
    },

    calculateReceivedQuantity(receiptLines) {
        return receiptLines.reduce((total, line) => total + parseInt(line.received_qty), 0);
    }
};

export const OrderNumberService = {
    getNewOrderNumber() {
        return axios.get('/new-order-number')
            .then(response => {
                return response.data.orderNumber;
            })
            .catch(error => {
                console.error('There was an error generating a new order number:', error);
            });
    },
};
