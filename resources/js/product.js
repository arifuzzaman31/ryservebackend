require('./vue-assets');

import { createApp } from 'vue';
import ViewProduct from './components/product/ViewProduct.vue';
import CreateProduct from './components/product/CreateProduct.vue';
import EditProduct from './components/product/EditProduct.vue';
import VatTax from './components/company/VatTax.vue';
import VLazyImage from "v-lazy-image";

const app = createApp({})
app.component('view-product', ViewProduct)
app.component('create-product', CreateProduct)
app.component('edit-product', EditProduct)
app.component('view-tax', VatTax)
app.component('v-lazy-image',VLazyImage)
app.mount('#app')