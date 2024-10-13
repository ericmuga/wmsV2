<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { ref, onMounted ,computed} from 'vue';
import { OrderService,OrderNumberService } from '@/services/OrderService'; // Adjust the path if needed
import { ReceiptService,ReceiptNumberService } from '@/services/ReceiptService'; // Adjust the path if needed
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import Modal from '@/Components/Modal.vue';
import Modal2 from '@/Components/Modal.vue';
import { VendorService } from '@/services/VendorService';
import { ModeOfPaymentService } from '@/services/ModeOfPaymentService';
import { CountyService } from '@/services/CountyService';
import { DeliveryModeService } from '@/services/DeliveryModeService';
import { MileageCodeService } from '@/services/MileageCodeService';
import { ItemService } from '@/services/ItemService';
import { PenService } from '@/services/PenService';
import axios from 'axios';


const showModal2 = ref(false);
const selectedOrder = ref(null);
// const receipt_lines = ref([]);
const pendingOrderOptions = ref([]); // Will contain only Pending orders

// Fetch the orders with status 'Pending' for the dropdown


// Populate receipt details when an order is selected
const populateReceiptDetails = () => {
  const selected = pendingOrderOptions.value.find(order => order.order_no === selectedOrder.value);

  if (selected) {
    // Save the current receipt_no before updating receipt
    const currentReceiptNo = receipt.value.receipt_no;



    // delete selected.order_lines;
    // Populate receipt with selected order data
    receipt.value = {
      ...selected,
      receipt_no: currentReceiptNo,  // Reassign the saved receipt_no
      cess_paid:false,
      no_objection:false,
      purchase_order_id:selected.id
    };

   delete receipt.value.id;

    // Populate receipt lines and make the required modifications
    receipt_lines.value = selected.order_lines.map(line => {
      return {
        ...line,  // Spread the existing line details
        order_qty: parseInt(line.order_qty),  // Convert order_qty to an integer
        posting_date: today(),
        received_qty: null,  // Add received_qty as empty (null)
        identifier: '',  // Add identifier as an empty string
        order_line_id: line.id
      };
    });
  }

  delete receipt.value.order_lines;
  delete receipt.value.updated_at;
  delete receipt.value.created_at;
};



const orders = ref([]);
const receipts = ref([]);

// Function to calculate the sum of order quantities
// const calculateOrderQuantity = (orderLines) => {
//     return orderLines.reduce((total, line) => total + parseInt(line.order_qty), 0);
// };

// // Function to calculate received quantity based on status (can be adjusted based on receipt data)
// const calculateReceivedQuantity = (receiptLines, status) => {
//     if (status === 'Posted') {
//         return receiptLines.reduce((total, line) => total + parseInt(line.received_qty), 0); // Assume all are received
//     }
//     return 0; // Otherwise, assume 0 received
// };

const calculateTotalOrderQuantity = (orderLines) => {
    if (!orderLines || orderLines.length === 0) return 0;
    return orderLines.reduce((total, line) => {
        return total + (line.order_qty ? parseInt(line.order_qty) : 0);
    }, 0);
};

// Function to calculate total received quantity from receipt_lines inside order_lines
const calculateTotalReceivedQuantity = (orderLines) => {
    if (!orderLines || orderLines.length === 0) return 0;
    return orderLines.reduce((total, line) => {
        if (line.receipt_lines && line.receipt_lines.length > 0) {
            return total + line.receipt_lines.reduce((subTotal, receiptLine) => {
                return subTotal + (receiptLine.received_qty ? parseInt(receiptLine.received_qty) : 0);
            }, 0);
        }
        return total;
    }, 0);
};

// Function to calculate total order quantity from receiptLines
const calculateOrderQuantityInReceipts = (receiptLines) => {
    if (!receiptLines) return 0; // Add this check to prevent the undefined error
    return receiptLines.reduce((total, line) => {
        return total + (line.order_line ? parseInt(line.order_line.order_qty) : 0);
    }, 0);
};

// Function to calculate total received quantity from receiptLines
const calculateReceivedQuantityInReceipts = (receiptLines) => {
    if (!receiptLines) return 0; // Add this check to prevent the undefined error
    return receiptLines.reduce((total, line) => {
        return total + (line.received_qty ? parseInt(line.received_qty) : 0);
    }, 0);
};

// Function for status coloring (optional)

const showModal=ref(false);
// const showModal2=ref(false);
const itemOptions = ref([]);
const penOptions = ref([]);
const today = () => {
    const date = new Date();
    return date.toISOString().substring(0, 10); // Returns 'YYYY-MM-DD' format
};
const vendors = ref([]);
const vendorOptions = ref([]);
const selectedVendor = ref(null);
const modeOfPaymentOptions = ref([]);
const countyOptions = ref([]);
const deliveryModeOptions = ref([]);
const mileageCodeOptions = ref([]);

const addNewOrder = () => {


    order.value={status:'Open',posting_date:today()}
    order_lines.value=[];
    showModal.value = true;
    // Fetch a new order number when the modal opens
    OrderNumberService.getNewOrderNumber().then((newOrderNo) => {
        order.value.order_no = newOrderNo;
    });
};


const addNewReceipt=()=>{
    showModal.value=false;
    showModal2.value=true;
    selectedOrder.value=null;
     receipt.value={status:'Open',posting_date:today()}
    receipt_lines.value=[];

    // Fetch a new order number when the modal opens
    ReceiptNumberService.getNewReceiptNumber().then((newReceiptNo) => {
        receipt.value.receipt_no = newReceiptNo;
    });
}

// Order form data with default values
const order = ref({
    order_no: '',
    posting_date: today(),  // Set the posting date to today's date
    status: 'Open',  // Default status to 'Open'
    delivery_mode: '',
    delivery_date: null,
    delivery_time: null,
    vendor_no: null,
    vendor_name: '',
    mode_of_payment: '',
    haulier_no: '',
    phone_no: '',
    contact_name: '',
    county: '',
    mileage_code: '',
});

const receipt = ref({
    order_no: '',
    receipt_no:'',
    purchase_order_id:null,
    posting_date: today(),  // Set the posting date to today's date
    status: 'Open',  // Default status to 'Open'
    delivery_mode: '',
    delivery_date: null,
    delivery_time: null,
    vendor_no: null,
    vendor_name: '',
    mode_of_payment: '',
    haulier_no: '',
    phone_no: '',
    contact_name: '',
    county: '',
    mileage_code: '',
    cess_paid:false,
    no_objection:false
});

const getStatusClass = (status) => {
    return status === 'Received' ? 'bg-lime-500 text-white p-1 rounded' :
           status === 'Pending' ? 'bg-amber-500 text-white p-1 rounded' : '';
};

// Order lines form data
const order_lines = ref([
    { item_no: '', order_qty: 0 }
]);

const receipt_lines = ref([
    { item_no: '', received_qty: 0,identifier:'',order_qty:0 ,pen_no:''}
]);
// Add new order line
const addOrderLine = () => {
    order_lines.value.push({ item_no: '', order_qty: null });
};

const addReceiptLine = () => {
    receipt_lines.value.push({ item_no: '', received_qty: null, identifier:'' });
};

const removeOrderLine = (index) => {
    order_lines.value.splice(index, 1);
};
const removeReceiptLine = (index) => {
    receipt_lines.value.splice(index, 1);
};


const isVendorSelected = computed(() => !!order.value.vendor_no);

// Handle form submission
const handleForm = async () => {
    order.value.status = 'Pending';
    console.log(order_lines.value); // Ensure this is an array before submission


    try {
        // Add the new order to the OrderService
        await OrderService.addOrder(order.value, order_lines.value);

         OrderService.getOrdersMini().then((data) => {
            orders.value = data;
        });

        // Clear the form after successful submission
        order.value = {
            order_no: '',
            posting_date: today(),  // Set the posting date to today's date
            status: 'Open',  // Default status to 'Open'
            delivery_mode: '',
            delivery_date: null,
            delivery_time: null,
            vendor_no: null,
            vendor_name: '',
            mode_of_payment: '',
            haulier_no: '',
            phone_no: '',
            contact_name: '',
            county: '',
            mileage_code: '',

        };

        order_lines.value = [{ item_no: '', quantity: null }];
        showModal.value=false;
        // alert('Order successfully created!');
    } catch (error) {
        console.error('There was an error adding the order:', error);
    }
};



const handleForm2 = async () => {
    receipt.value.status = 'Pending';

    console.log(receipt.value)

    delete receipt.value.id;
    delete receipt.value.created_at;
    delete receipt.value.update_at;



    try {
        // Add the new order to the OrderService
        await ReceiptService.addReceipt(receipt.value, receipt_lines.value);

        // Clear the form after successful submission
        receipt.value = {
            order_no: '',
            receipt_no:'',
            posting_date: today(),  // Set the posting date to today's date
            status: 'Pending',  // Default status to 'Open'
            delivery_mode: '',
            delivery_date: null,
            delivery_time: null,
            vendor_no: null,
            vendor_name: '',
            mode_of_payment: '',
            haulier_no: '',
            phone_no: '',
            contact_name: '',
            county: '',
            mileage_code: '',
            cess_paid:false,
            no_objection:false
        };

        receipt_lines.value = [{ item_no: '', quantity: null,identifier:'',order_line_id:null }];


    } catch (error) {
        console.error('There was an error adding the order:', error);
    }
    showModal2.value=false;
};


const updateVendorDetails = () => {
    const vendor = vendors.value.find(v => v.vendor_no === selectedVendor.value);
    if (vendor) {
        order.value.vendor_name = vendor.vendor_name;
        order.value.mode_of_payment = vendor.mode_of_payment;
        order.value.haulier_no = vendor.haulier_no;
        order.value.phone_no = vendor.phone_no;
        order.value.contact_name = vendor.contact_name;
        order.value.county = vendor.county;
        order.value.mileage_code = vendor.mileage_code;
        order.value.delivery_mode= vendor.delivery_mode;
        order.value.vendor_no=selectedVendor.value;
        // alert(order.vendor_no)
    }
};


onMounted(() => {
    OrderService.getOrders().then((data) => {
        orders.value = data;
    });

     OrderService.getPendingOrders()
        .then(orders => {
        pendingOrderOptions.value = orders;
        })
        .catch(error => {
        console.error('Error loading pending orders:', error);
        });
    });




     ReceiptService.getReceipts().then((data) => {
        receipts.value = data;
    });

    VendorService.getVendors().then((data) => {
        vendors.value = data;
        vendorOptions.value = data.map(vendor => ({
            vendor_no: vendor.vendor_no,
            vendorLabel: `${vendor.vendor_no} | ${vendor.vendor_name}`, // Display as vendor_no | vendor_name
        }));

    // Fetch options for Mode of Payment
    ModeOfPaymentService.getModesOfPayment().then((data) => {
        modeOfPaymentOptions.value = data;


    });
 ItemService.getItems().then((data) => {
        itemOptions.value = data;

         itemOptions.value = data.map(item => ({
            item_no: item.item_no,
            description: `${item.item_no} | ${item.description}`, // Display as vendor_no | vendor_name
        }));
    });

     PenService.getPens().then((data) => {
        penOptions.value = data;
    });
    // Fetch options for County
    CountyService.getCounties().then((data) => {
        countyOptions.value = data;
    });

    // Fetch options for Delivery Mode
    DeliveryModeService.getDeliveryModes().then((data) => {
        deliveryModeOptions.value = data;
    });

    // Fetch options for Mileage Code
    MileageCodeService.getMileageCodes().then((data) => {
        mileageCodeOptions.value = data;
    });
    });


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-center text-gray-800 dark:text-gray-200"
            >
                Lairage
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="card">
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">Purchase Order</Tab>
                                <Tab value="1">Purchase Receipt</Tab>
                                <Tab value="2">Transfers</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div class="grid grid-cols-4 ">

                                    <div class="col-span-3 p-2 shadow-sm border-stone-100">
                                    <Toolbar>

                                        <template #start>
                                            <Button icon="pi pi-plus" class="mr-2" severity="secondary" text @click="addNewOrder"/>
                                            <!-- <Button icon="pi pi-print" class="mr-2" severity="secondary" text /> -->
                                            <!-- <Button icon="pi pi-upload" severity="secondary" text /> -->
                                        </template>

                                        <template #center>
                                            <IconField>

                                                <InputText placeholder="Search" />
                                            </IconField>
                                        </template>

                                        <template #end> <SplitButton label="Save" :model="items"></SplitButton></template>

                                    </Toolbar>


                                        <div class="card">

                                            <DataTable :value="orders" tableStyle="min-width: 50rem">
                                            <!-- Standard Columns for Order Info -->
                                            <Column field="order_no" header="Order No."></Column>
                                            <Column field="vendor_no" header="Vendor No."></Column>
                                            <Column field="vendor_name" header="Vendor Name"></Column>
                                            <Column field="posting_date" header="Posting Date"></Column>

                                            <Column header="Order Quantity">
                                                <template #body="slotProps">
                                                    {{ calculateTotalOrderQuantity(slotProps.data.order_lines) }}
                                                </template>
                                            </Column>

                                            <!-- Received Quantity Column -->
                                            <Column header="Received Quantity">
                                                <template #body="slotProps">
                                                    {{ calculateTotalReceivedQuantity(slotProps.data.order_lines) }}
                                                </template>
                                            </Column>

                                            <!-- Status Column with Conditional Coloring -->
                                            <Column header="Status">
                                                <template #body="slotProps">
                                                    <span :class="getStatusClass(slotProps.data.status)">
                                                        {{ slotProps.data.status }}
                                                    </span>
                                                </template>
                                            </Column>
                                        </DataTable>

                                        </div>


                                    </div>
                                     <div>
                                        <div class="flex flex-col gap-2 text-center">
                                            <div class="p-2 bg-teal-200 rounded-lg">Today</div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Rosemark</div>
                                                    <div> 12</div>
                                            </div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Third Party</div>
                                                    <div> 72</div>
                                            </div>
                                            <div><hr></div>
                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Total</div>
                                                    <div> 84</div>
                                            </div>


                                        </div>
                                          <div class="flex flex-col gap-2 text-center">
                                            <div class="p-2 rounded-lg bg-slate-400">Yesterday</div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Rosemark</div>
                                                    <div> 12</div>
                                            </div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Third Party</div>
                                                    <div> 72</div>
                                            </div>
                                            <div><hr></div>
                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Total</div>
                                                    <div> 84</div>
                                            </div>


                                        </div>

                                     </div>

                                    </div>
                                </TabPanel>
                                <TabPanel value="1">
                                      <div class="grid grid-cols-4 ">

                                    <div class="col-span-3 p-2 shadow-sm border-stone-100">
                                    <Toolbar>

                                        <template #start>
                                            <Button icon="pi pi-plus" class="mr-2" severity="secondary" text @click="addNewReceipt"/>
                                            <!-- <Button icon="pi pi-print" class="mr-2" severity="secondary" text /> -->
                                            <!-- <Button icon="pi pi-upload" severity="secondary" text /> -->
                                        </template>

                                        <template #center>
                                            <IconField>

                                                <InputText placeholder="Search Receipt" />
                                            </IconField>
                                        </template>



                                    </Toolbar>


                                        <div class="card">

                                            <DataTable :value="receipts" tableStyle="min-width: 50rem">
                                                <!-- Standard Columns for Order Info -->
                                                <Column field="order_no" header="Order No."></Column>
                                                <Column field="receipt_no" header="Receipt No."></Column>
                                                <Column field="vendor_no" header="Vendor No."></Column>
                                                <Column field="vendor_name" header="Vendor Name"></Column>
                                                <Column field="posting_date" header="Posting Date"></Column>

                                                <!-- Order Quantity Column -->
                                                <Column header="Order Quantity">
                                                    <template #body="slotProps">
                                                        {{ calculateOrderQuantityInReceipts(slotProps.data.receipt_lines) }}
                                                    </template>
                                                </Column>

                                                <!-- Received Quantity Column -->
                                                <Column header="Received Quantity">
                                                    <template #body="slotProps">
                                                        {{ calculateReceivedQuantityInReceipts(slotProps.data.receipt_lines) }}
                                                    </template>
                                                </Column>
    </DataTable>

                                        </div>


                                    </div>
                                     <div>
                                        <div class="flex flex-col gap-2 text-center">
                                            <div class="p-2 bg-teal-200 rounded-lg">Today</div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Rosemark</div>
                                                    <div> 12</div>
                                            </div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Third Party</div>
                                                    <div> 72</div>
                                            </div>
                                            <div><hr></div>
                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Total</div>
                                                    <div> 84</div>
                                            </div>


                                        </div>
                                          <div class="flex flex-col gap-2 text-center">
                                            <div class="p-2 rounded-lg bg-slate-400">Yesterday</div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Rosemark</div>
                                                    <div> 12</div>
                                            </div>

                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Third Party</div>
                                                    <div> 72</div>
                                            </div>
                                            <div><hr></div>
                                            <div class="flex flex-row justify-between px-5 ">
                                                    <div>Total</div>
                                                    <div> 84</div>
                                            </div>


                                        </div>

                                     </div>

                                    </div>
                                </TabPanel>
                                <TabPanel value="2">
                                    <p class="m-0">
                                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa
                                        qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus.
                                    </p>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
         <Modal :show="showModal" @close="showModal = false" maxWidth='2xl'>
        <form @submit.prevent="handleForm" class="m-6">
            <div class="p-3 mx-5 my-3 font-bold text-center text-black uppercase bg-teal-200 rounded-md">Purchase Order</div>

            <!-- Order Data Form -->
            <div class="grid grid-cols-2 gap-1 p-1 text-sm">
                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="order_no">Order No.</label>
                    <InputText v-model="order.order_no" id="order_no"  readonly />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="posting_date">Posting Date</label>
                    <input type="date" v-model="order.posting_date" id="posting_date" class="rounded-lg"/>
                </div>

                 <div class="flex flex-row items-center justify-between gap-1">
                    <label for="delivery_date">Delivery Date</label>
                    <input type="date" v-model="order.delivery_date" id="delivery_date" class="rounded-lg"/>
                </div>

                <div class="flex flex-row items-center justify-between">
                    <label for="delivery_time">Delivery Time</label>
                    <input type="time" v-model="order.delivery_time" id="delivery_time" timeOnly class="rounded-lg"/>
                </div>

                <div class="flex flex-row items-center justify-between">
                            <label for="vendor_no">Vendor No.</label>
                            <Select
                                v-model="selectedVendor"
                                :options="vendorOptions"
                                optionLabel="vendorLabel"
                                optionValue="vendor_no"
                                class="w-full"
                                @change="updateVendorDetails"
                            />
                            <input type="text" v-model="order.vendor_no" hidden/>
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="vendor_name">Vendor Name</label>
                    <InputText v-model="order.vendor_name" id="vendor_name" />
                </div>

               <div class="flex flex-row items-center justify-between gap-1">
                            <label for="county">County</label>
                            <Select
                                v-model="order.county"
                                :options="countyOptions"
                                 optionLabel="description"
                                 optionValue="code"
                                class="w-full"
                            />
                        </div>

               <!-- Select for Mileage Code -->
                <div>
                    <label for="mileage_code">Mileage Code</label>
                    <Select
                        v-model="order.mileage_code"
                        :options="mileageCodeOptions"
                         optionLabel="description"
                        optionValue="code"
                        class="w-full"
                    />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="status">Status</label>
                    <InputText v-model="order.status" id="status" readonly />
                </div>

                <!-- Select for Delivery Mode -->
                    <div class="flex flex-row items-center justify-between gap-1">
                        <label for="delivery_mode">Delivery Mode</label>
                        <Select
                            v-model="order.delivery_mode"
                            :options="deliveryModeOptions"
                             optionLabel="description"
                                optionValue="code"
                            class="w-full"
                        />
                    </div>



                <div class="flex flex-row items-center justify-between gap-1">
                            <label for="mode_of_payment">Mode of Payment</label>
                            <Select
                                v-model="order.mode_of_payment"
                                :options="modeOfPaymentOptions"
                                optionLabel="description"
                                optionValue="code"
                                class="w-full"
                            />
                        </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="haulier_no">Haulier No.</label>
                    <InputText v-model="order.haulier_no" id="haulier_no" />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="phone_no">Phone No.</label>
                    <InputText v-model="order.phone_no" id="phone_no" />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="contact_name">Contact Name</label>
                    <InputText v-model="order.contact_name" id="contact_name" />
                </div>
            </div>

            <!-- Order Lines Data Form -->
            <div class="p-4">
                <h3 class="mb-4 font-bold">Order Lines</h3>

                <div v-for="(line, index) in order_lines" :key="index" class="flex flex-row items-center gap-1 text-sm place-items-center">
                      <Select
                        v-model="line.item_no"
                        :options="itemOptions"
                        optionLabel="description"
                        optionValue="item_no"

                        @change="updateItemDescription(index)"
                        style="height: 50%;"
                        />


                    <div>
                        <label for="order_qty">Order Quantity</label>
                        <InputNumber v-model="line.order_qty" class="" />
                    </div>
                <!-- Remove Order Line Button -->
                    <div class="flex items-center">
                        <Button
                            icon="pi pi-times"
                            class="p-button-rounded p-button-danger"
                            @click="removeOrderLine(index)"

                        />
                    </div>
                </div>

                <Button label="+ Add Line" @click="addOrderLine" class="mt-2 p-button-sm" :disabled="!isVendorSelected"/>
            </div>

            <!-- Submit and Close Buttons -->
            <div class="flex flex-row justify-center gap-2 mt-4">
                <Button label="Post" type="submit" icon="pi pi-send" />
                <Button label="Close" type="button" severity="danger" icon="pi pi-trash" @click="showModal = false" />
            </div>
        </form>
    </Modal>

    <Modal2 :show="showModal2" @close="showModal2 = false" maxWidth='2xl'>
        <form @submit.prevent="handleForm2" class="m-6">
            <div class="p-3 mx-5 my-3 font-bold text-center text-black uppercase rounded-md bg-cyan-400">Purchase Receipt</div>

            <!-- Order Data Form -->
            <div class="grid grid-cols-2 gap-1 p-1 text-sm">
                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="order_no">Receipt No.</label>
                    <InputText v-model="receipt.receipt_no" id="order_no"  readonly disabled/>
                </div>
                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="order_no">Order No. (Pending)</label>
                    <Select
                    v-model="selectedOrder"
                    :options="pendingOrderOptions"
                    optionLabel="order_no"
                    optionValue="order_no"
                    @change="populateReceiptDetails"
                    class="w-full"
                    />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="posting_date">Posting Date</label>
                    <input type="date" v-model="receipt.posting_date" id="posting_date" class="rounded-lg"/>
                </div>

                 <div class="flex flex-row items-center justify-between gap-1">
                    <label for="delivery_date">Delivery Date</label>
                    <input type="date" v-model="receipt.delivery_date" id="delivery_date" class="rounded-lg" />
                </div>

                <div class="flex flex-row items-center justify-between">
                    <label for="delivery_time">Delivery Time</label>
                    <input type="time" v-model="receipt.delivery_time" id="delivery_time" timeOnly class="rounded-lg" />
                </div>

                <div class="flex flex-row items-center justify-between">
                            <label for="vendor_no">Vendor No.</label>

                            <InputText type="text" v-model="receipt.vendor_no" disabled/>
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="vendor_name">Vendor Name</label>
                    <InputText v-model="receipt.vendor_name" id="vendor_name" disabled/>
                </div>

               <div class="flex flex-row items-center justify-between gap-1">
                            <label for="county">County</label>
                            <Select
                                v-model="receipt.county"
                                :options="countyOptions"
                                 optionLabel="description"
                                 optionValue="code"
                                class="w-full"
                            />
                        </div>

               <!-- Select for Mileage Code -->
                <div>
                    <label for="mileage_code">Mileage Code</label>
                    <Select
                        v-model="receipt.mileage_code"
                        :options="mileageCodeOptions"
                         optionLabel="description"
                        optionValue="code"
                        class="w-full"
                    />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="status">Status</label>
                    <InputText v-model="receipt.status" id="status" disabled/>
                </div>

                <!-- Select for Delivery Mode -->
                    <div class="flex flex-row items-center justify-between gap-1">
                        <label for="delivery_mode">Delivery Mode</label>
                        <Select
                            v-model="receipt.delivery_mode"
                            :options="deliveryModeOptions"
                             optionLabel="description"
                                optionValue="code"
                            class="w-full"
                        />
                    </div>



                <div class="flex flex-row items-center justify-between gap-1">
                            <label for="mode_of_payment">Mode of Payment</label>
                            <Select
                                v-model="receipt.mode_of_payment"
                                :options="modeOfPaymentOptions"
                                optionLabel="description"
                                optionValue="code"
                                class="w-full"
                            />
                        </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="haulier_no">Haulier No.</label>
                    <InputText v-model="receipt.haulier_no" id="haulier_no" />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="phone_no">Phone No.</label>
                    <InputText v-model="receipt.phone_no" id="phone_no" />
                </div>

                <div class="flex flex-row items-center justify-between gap-1">
                    <label for="contact_name">Contact Name</label>
                    <InputText v-model="receipt.contact_name" id="contact_name" />
                </div>
                <div class="flex flex-wrap justify-center gap-4 card">
                  <div class="flex items-center">
                    <Checkbox v-model="receipt.no_objection" binary="true" />
                    <label for="no_objection" class="ml-2"> No Objection </label>
                </div>

                <div class="flex items-center">
                    <Checkbox v-model="receipt.cess_paid" binary="true" />
                    <label for="cess_paid" class="ml-2"> Cess Paid </label>
                </div>

                </div>
            </div>

            <!-- Order Lines Data Form -->
            <div class="p-4">
                <h3 class="mb-4 font-bold">Receipt Lines</h3>

                <div v-for="(line, index) in receipt_lines" :key="index" class="flex flex-row items-center justify-center gap-1 text-sm place-items-center">
                      <div class="flex flex-col">
                        <label for="description">Item</label>
                        <!-- <InputText v-model="line.item_no" disabled/> -->
                         <Select
                        v-model="line.item_no"
                        :options="itemOptions"
                        optionLabel="description"
                        optionValue="item_no"
                        style="height: 50%; "

                        disabled
                        />
                      </div>



                    <div>
                        <label for="order_qty">Ord_qty</label>
                        <input type="text"  v-model="line.order_qty"  size="3" class="rounded-lg bg-slate-200 " disabled />
                    </div>
                    <div>
                        <label for="received_qty">Rcp_qty</label>
                        <input type="text"  v-model="line.received_qty" size="3" class="rounded-lg" />
                    </div>

                    <div class="flex flex-col">
                        <label for="description">Pen</label>

                         <Select
                        v-model="line.pen_no"
                        :options="penOptions"
                        optionLabel="description"
                        optionValue="pen_no"
                        style="height: 50%;"

                        />
                      </div>

                     <div>
                        <label for="identifier">Identifier</label>
                        <InputText v-model="line.identifier"  />
                    </div>

                </div>


            </div>

            <!-- Submit and Close Buttons -->
            <div class="flex flex-row justify-center gap-2 mt-4">
                <Button label="Post" type="submit" icon="pi pi-send" />
                <Button label="Close" type="button" severity="danger" icon="pi pi-trash" @click="showModal2 = false" />
            </div>
        </form>
    </Modal2>
</template>
