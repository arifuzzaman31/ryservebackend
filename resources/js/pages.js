require('./vue-assets');

import { createApp } from 'vue';
import HomePage from './components/pages/HomePage.vue';
import ViewPage from './components/pages/ViewPage.vue';
import SectionProduct from './components/pages/SectionProduct.vue';
import Shipping from './components/pages/Shipping.vue';
import Media from './components/pages/MediaManager.vue';
import ViewInformation from './components/pages/ViewInformation.vue';
import VLazyImage from "v-lazy-image";

const app = createApp({})
app.component('view-homepage', HomePage)
app.component('view-page', ViewPage)
app.component('section-product', SectionProduct)
app.component('view-shipping', Shipping)
app.component('view-information', ViewInformation)
app.component('view-media', Media)
app.component('v-lazy-image',VLazyImage)
app.mount('#app')
