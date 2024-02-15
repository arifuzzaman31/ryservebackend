require('./vue-assets');

import { createApp } from 'vue';
import ViewCategory from './components/category/ViewCategory.vue';
import EditCategory from './components/category/EditCategory.vue';
import CategoryUpdate from './components/category/CategoryUpdate.vue';
import VLazyImage from "v-lazy-image";

const app = createApp({})
app.component('view-category', ViewCategory)
app.component('update-category', CategoryUpdate)
app.component('edit-category', EditCategory)
app.component('v-lazy-image',VLazyImage)
app.mount('#app')