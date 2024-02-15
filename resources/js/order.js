require('./vue-assets');

import { createApp } from 'vue';
import ViewOrder from './components/order/ViewOrder.vue';
import DetailsOrder from './components/order/DetailsOrder.vue';
import ViewCustomer from './components/customer/ViewCustomer.vue';
import ViewCustomerOrder from './components/customer/ViewCustomerOrder.vue';

const app = createApp({})

app.component('view-order', ViewOrder)
app.component('detail-order', DetailsOrder)
app.component('view-customer', ViewCustomer)
app.component('customer-order', ViewCustomerOrder)
app.mount('#app')