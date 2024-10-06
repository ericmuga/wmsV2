// services/VendorService.js
export const VendorService = {
    getVendors() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve([
                    {
                        vendor_no: 'V1001',
                        vendor_name: 'Vendor A',
                        mode_of_payment: 'EFT',
                        haulier_no: 'H001',
                        phone_no: '123-456-789',
                        contact_name: 'John Doe',
                        county: 'C001',
                        mileage_code: 'T060',
                        delivery_mode:'SELF'
                    },
                    {
                        vendor_no: 'V1002',
                        vendor_name: 'Vendor B',
                        mode_of_payment: 'Cash',
                        haulier_no: 'H002',
                        phone_no: '987-654-321',
                        contact_name: 'Jane Doe',
                        county: 'C002',
                        mileage_code: 'T160',
                        delivery_mode:'COMPANY'
                    },
                ]);
            }, 1000); // Simulate an API call with a 1 second delay
        });
    },
};
