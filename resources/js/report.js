require('./vue-assets');

import { createApp } from 'vue';
// import OrderReport from './components/report/OrderReport.vue';
import RevenueReport from './components/report/RevenueReport.vue';
import UpcomingReserve from './components/report/UpcomingReserve.vue';

const app = createApp({})

// app.component('order-report', OrderReport)
app.component('revenue-report', RevenueReport)
app.component('upcoming-reserve', UpcomingReserve)

app.mount('#app')
