require('./vue-assets');

import { createApp } from 'vue';
import ViewRefund from './components/refund/ViewRefund.vue';
// import ApproveRefund from './components/refund/ApproveRefund.vue';
import ViewConfig from './components/refund/ViewConfig.vue';

const app = createApp({})

app.component('view-refund', ViewRefund)
// app.component('approve-refund', ApproveRefund)
app.component('view-config', ViewConfig)

app.mount('#app')