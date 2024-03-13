require('./vue-assets');

import { createApp } from 'vue';
// import OrderReport from './components/report/OrderReport.vue';
import RevenueReport from './components/report/RevenueReport.vue';

const app = createApp({})

// app.component('order-report', OrderReport)
app.component('revenue-report', RevenueReport)

app.mount('#app')
