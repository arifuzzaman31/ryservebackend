require('./vue-assets');

import { createApp } from 'vue';
// import OrderReport from './components/report/OrderReport.vue';
import StockReport from './components/report/StockReport.vue';
import paymentReport from './components/report/paymentReport.vue';
import IndividualCustomerReport from './components/report/IndividualCustomerReport.vue';
import InvoiceReport from './components/report/InvoiceReport.vue';
import CustomerRefundReport from './components/report/CustomerRefundReport.vue';
import CustomerLifetimeReport from './components/report/CustomerLifetimeReport.vue';
import salesReport from './components/report/SalesReport.vue';
import CampaignReport from './components/report/CampaignReport.vue';

const app = createApp({})

// app.component('order-report', OrderReport)
app.component('stock-report', StockReport)
app.component('payment-report', paymentReport)
app.component('individual-customer-report', IndividualCustomerReport)
app.component('invoice-report', InvoiceReport)
app.component('customer-refund-report', CustomerRefundReport)
app.component('customer-lifetime-report', CustomerLifetimeReport)
app.component('sales-report', salesReport)
app.component('campaign-report', CampaignReport)
app.mount('#app')