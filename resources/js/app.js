require('./vue-assets');

import { createApp } from 'vue';
import ViewLogin from './components/auth/Login.vue';
import ViewRole from './components/auth/ViewRole.vue';
import VeiwEmployee from './components/employee/VeiwEmployee.vue';
import VeiwVendor from './components/employee/VeiwVendor.vue';
import Dashboard from './components/Dashboard.vue';

const app = createApp({})

app.component('view-login', ViewLogin)
app.component('view-role', ViewRole)
app.component('view-employee', VeiwEmployee)
app.component('view-vendor', VeiwVendor)
app.component('view-dashboard', Dashboard)


app.mount('#app')
