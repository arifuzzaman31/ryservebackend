import { createRouter, createWebHistory } from 'vue-router'

// import CreateProduct from '../components/product/CreateProduct.vue'

const routes = [
    {
        path: '/admin/create/product',
        name: 'admin.create.product',
        component: require('../components/product/test.vue')
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
})