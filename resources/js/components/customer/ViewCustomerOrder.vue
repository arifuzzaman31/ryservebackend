<script>
import { ref,onMounted } from 'vue';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    props: ['customer'],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            orders : [],
            loading :false
        }
    },

    methods : {
        getOrder(page=1){
            this.loading = true
            let response = axios.get(baseUrl+`get-user-order/${this.customer.id}?page=${page}&per_page=10`)
            this.orders = response.data
            this.loading = false
        }
    },
    mounted(){
        this.getOrder()
    }
    // setup(props) {
    //     const orders = ref('')
    //     const user_id = ref('')
    //     const getOrder = () => {
    //         user_id = props.customer.id
    //         console.log(user_id)
    //     }
    //     onMounted(()=> getOrder())

    //     return {
    //         orders,
    //         user_id
    //     }
    // },
}
</script>
<template>
    <div class="widget-content widget-content-area" v-if="loading">
        <div>
            <table class="table table-bordered table-hover mb-4">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>OrderID</th>
                        <th>Price</th>
                        <th>Shipping</th>
                        <th>PaymentBy</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(order,index) in orders.data" :key="index">
                        <tr>
                            <td>{{ index+1 }}</td>
                            <td>{{ order.order_id }}</td>
                            <td>{{ order.total_price }}</td>
                            <td>{{ order.shipping_method }}</td>
                            <td>{{ order.payment_method }}</td>
                            <td class="text-center">
                                <span v-if="order.status !=0">
                                    <span v-if="order.order_position == 0" class="badge badge-info">Pending</span>
                                    <span v-if="order.order_position == 1" class="badge badge-primary">On Process</span>
                                    <span v-if="order.order_position == 2" class="badge badge-warning">On Delivery</span>
                                    <span v-if="order.order_position == 3" class="badge badge-success">Delivered</span>
                                </span>
                                <span v-else class="badge badge-danger">Cancel</span>
                            </td>
                            <td>
                                <div class="dropdown custom-dropdown">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink1" style="will-change: transform;">
                                        <a class="dropdown-item" href="javascript:void(0);">Order View</a>
                                        <a type="button" class="dropdown-item" href="javascript:void(0);">Cancel</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <Bootstrap4Pagination
                :data="orders"
                @pagination-change-page="getOrder"
            />
        </div>
    </div>
</template>
