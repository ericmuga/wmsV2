<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Toolbar from 'primevue/toolbar';
import { ref, onMounted } from 'vue';
import { OrderService } from '@/services/OrderService'; // Adjust the path if needed
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import Modal from '@/Components/Modal.vue';
import { VendorService } from '@/services/VendorService';
import { ModeOfPaymentService } from '@/services/ModeOfPaymentService';
import { CountyService } from '@/services/CountyService';
import { DeliveryModeService } from '@/services/DeliveryModeService';
import { MileageCodeService } from '@/services/MileageCodeService';

const orders = ref([]);
const showModal=ref(false);
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



// Order form data with default values
const order = ref({
    order_no: '',
    posting_date: today(),  // Set the posting date to today's date
    status: 'Open',  // Default status to 'Open'
    delivery_mode: '',
    delivery_date: null,
    delivery_time: null,
    vendor_no: '',
    vendor_name: '',
    mode_of_payment: '',
    haulier_no: '',
    phone_no: '',
    contact_name: '',
    county: '',
    mileage_code: '',
});

// Order lines form data
const order_lines = ref([
    { item_no: '', item_description: '', order_qty: null }
]);

// Add new order line
const addOrderLine = () => {
    order_lines.value.push({ item_no: '', item_description: '', order_qty: null });
};
const removeOrderLine = (index) => {
    order_lines.value.splice(index, 1);
};

// Handle form submission
const handleForm = () => {
    const formData = {
        order: order.value,
        order_lines: order_lines.value
    };

    console.log('Submitted Data:', formData);
    // Submit the form data to the backend API (e.g., using Axios)
    // axios.post('/your-endpoint', formData);
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
        order.delivery_mode= vendor.delivery_mode;
    }
};

onMounted(() => {
    OrderService.getOrdersMini().then((data) => {
        orders.value = data;
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
                                            <Button icon="pi pi-plus" class="mr-2" severity="secondary" text @click="showModal=true"/>
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
                                                <Column field="Order No." header="Order No."></Column>
                                                <Column field="Vendor No." header="Vendor No."></Column>
                                                <Column field="Vendor Name" header="Vendor Name"></Column>
                                                <Column field="Posting Date" header="Posting Date"></Column>
                                                <Column field="Order Quantity" header="Order Quantity"></Column>
                                                <Column field="Received Quantity" header="Received Quantity"></Column>
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
                                    <p class="m-0">
                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim
                                        ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Consectetur, adipisci velit, sed quia non numquam eius modi.
                                    </p>
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
                    <InputText v-model="order.order_no" id="order_no" />
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
                    <InputText v-model="order.status" id="status" />
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

                <div v-for="(line, index) in order_lines" :key="index" class="flex flex-row gap-4 mb-2 text-sm">
                    <div>
                        <label for="item_no">Item No.</label>
                        <InputText v-model="line.item_no" type="text" class="w-full" />
                    </div>

                    <div>
                        <label for="item_description">Item Description</label>
                        <InputText v-model="line.item_description" type="text" class="w-full" />
                    </div>

                    <div>
                        <label for="order_qty">Order Quantity</label>
                        <InputNumber v-model="line.order_qty" class="w-full" />
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

                <Button label="+ Add Line" @click="addOrderLine" class="mt-2 p-button-sm" />
            </div>

            <!-- Submit and Close Buttons -->
            <div class="flex flex-row justify-center gap-2 mt-4">
                <Button label="Post" type="submit" icon="pi pi-send" />
                <Button label="Close" type="button" severity="danger" icon="pi pi-trash" @click="showModal = false" />
            </div>
        </form>
    </Modal>
</template>
