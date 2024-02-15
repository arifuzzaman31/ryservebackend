require('./vue-assets');

import { createApp } from 'vue';
import CreateBusiness from './components/business/CreateBusiness.vue';
import ViewBusiness from './components/business/ViewBusiness.vue';
import ViewAsset from './components/asset/ViewAsset.vue';
import CreateAsset from './components/asset/CreateAsset.vue';

const app = createApp({})
app.component('create-business', CreateBusiness)
app.component('view-business', ViewBusiness)
app.component('view-asset', ViewAsset)
app.component('create-asset', CreateAsset)
app.mount('#app')
