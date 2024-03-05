require('./vue-assets');

import { createApp } from 'vue';
import CreateBusiness from './components/business/CreateBusiness.vue';
import ViewBusiness from './components/business/ViewBusiness.vue';
import ViewAsset from './components/asset/ViewAsset.vue';
import CreateAsset from './components/asset/CreateAsset.vue';
import ViewSubAsset from './components/subasset/ViewSubAsset.vue';
import CreateSubAsset from './components/subasset/CreateSubAsset.vue';
import ViewSubAssetComp from './components/subassetcomp/ViewSubAssetComp.vue';
import CreateSubAssetComp from './components/subassetcomp/CreateSubAssetComp.vue';
import ViewRyservation from './components/booking/ViewRyservation.vue';
import ViewAmenities from './components/amenities/ViewAmenities.vue';

const app = createApp({})
app.component('create-business', CreateBusiness)
app.component('view-business', ViewBusiness)
app.component('view-asset', ViewAsset)
app.component('create-asset', CreateAsset)
app.component('view-subasset', ViewSubAsset)
app.component('create-subasset', CreateSubAsset)
app.component('view-subassetcomp', ViewSubAssetComp)
app.component('create-subassetcomp', CreateSubAssetComp)
app.component('view-ryservation', ViewRyservation)
app.component('view-amenities', ViewAmenities)
app.mount('#app')
