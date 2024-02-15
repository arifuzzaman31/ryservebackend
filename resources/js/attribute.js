require('./vue-assets');
// import router from './router'

import { createApp } from 'vue';
import ViewColor from './components/attribute/color/ViewColor.vue';
import ViewSize from './components/attribute/size/ViewSize.vue';
import ViewFabric from './components/attribute/fabric/ViewFabric.vue';
import ViewVendor from './components/attribute/vendor/ViewVendor.vue';
import ViewArtist from './components/attribute/artist/ViewArtist.vue';
import ViewBrand from './components/attribute/brand/ViewBrand.vue';
import ViewDesigner from './components/attribute/designer/ViewDesigner.vue';
import ViewEmbellishment from './components/attribute/embellishment/ViewEmbellishment.vue';
import ViewMaking from './components/attribute/making/ViewMaking.vue';
import ViewSeason from './components/attribute/season/ViewSeason.vue';
import ViewVariety from './components/attribute/variety/ViewVariety.vue';
import ViewFit from './components/attribute/fit/ViewFit.vue';
import ViewConsignment from './components/attribute/consignment/ViewConsignment.vue';
import ViewIngredient from './components/attribute/ingredient/ViewIngredient.vue';
import ViewCare from './components/attribute/care/ViewCare.vue';

const app = createApp({})
app.component('view-color', ViewColor)
app.component('view-size', ViewSize)
app.component('view-fabric', ViewFabric)
app.component('view-vendor', ViewVendor)
app.component('view-artist', ViewArtist)
app.component('view-brand', ViewBrand)
app.component('view-designer', ViewDesigner)
app.component('view-embellishment', ViewEmbellishment)
app.component('view-making', ViewMaking)
app.component('view-season', ViewSeason)
app.component('view-variety', ViewVariety)
app.component('view-fit', ViewFit)
app.component('view-consignment', ViewConsignment)
app.component('view-ingredient', ViewIngredient)
app.component('view-care', ViewCare)
// app.use(router)
app.mount('#app')