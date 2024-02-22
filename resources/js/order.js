require('./vue-assets');

import { createApp } from 'vue';
import UserMenu from './components/auth/UserMenu.vue';
const app = createApp({})

app.component('user-menu', UserMenu)
app.mount('#sidebar')
