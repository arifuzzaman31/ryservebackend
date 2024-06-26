require('./vue-assets');

import { createApp } from 'vue';
// import OrderReport from './components/report/OrderReport.vue';
import RevenueReport from './components/report/RevenueReport.vue';
import UpcomingReserve from './components/report/UpcomingReserve.vue';
import CompletedReserve from './components/report/CompletedReserve.vue';
import CanceledReserve from './components/report/CanceledReserve.vue';

const app = createApp({})

// app.component('order-report', OrderReport)
app.component('revenue-report', RevenueReport)
app.component('upcoming-reserve', UpcomingReserve)
app.component('completed-reserve', CompletedReserve)
app.component('cancel-reserve', CanceledReserve)

app.mount('#app')
