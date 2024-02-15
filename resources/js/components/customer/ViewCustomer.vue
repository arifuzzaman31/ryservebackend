<script>
import { ref,reactive,computed,onMounted } from 'vue';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
// import useOrder from '../../composables/order';

export default {
    components:{
        Bootstrap4Pagination
    },
    // data(){
    //     return {
    //         customers: [],
    //         keyword:''
    //     }
    // },
    // methods: {
    //     getCustomer(page = 1) {
    //         try{
    //              axios.get(baseUrl+`get-customer?page=${page}&keyword=${this.keyword}&per_page=1`)
    //             .then(response => {
    //                 this.customers = response.data
    //             }).catch(error => {
    //                 console.log(error)
    //             })
    //         }catch(e){
    //             console.log(e)
    //         }
    //     },
    //     openCustomerOrderModal(){
    //         $("#customerOrderModal").modal('show');
    //     },

    //     openCustomerOrderDetailModal(){
    //         // $("#customerOrderModal").modal('hide');
    //         $("#customerOrderDetailModal").modal('show');
    //     }
    // },
    // mounted(){
    //     this.getCustomer()
    // }
    setup() {
        const customers = ref([])
        const userOrders = ref([])
        const keyword = reactive({
            key:'',
            url : baseUrl
        })
        // const {userOrders,getUserOrder} = useOrder();
        
        const getCustomer = async(page = 1) => {
            try{
                await axios.get(baseUrl+`get-customer?page=${page}&keyword=${keyword.key}&per_page=12`)
                .then(response => {
                    customers.value = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        }
        const getUserOrder = async (id,page) => {
            let response = await axios.get(baseUrl+`get-user-order/${id}?page=${page}&per_page=1`)
            userOrders.value = response.data
        }
        const openCustomerOrderModal = async(id) => {
            getUserOrder(id,1)
            $("#customerOrderModal").modal('show');
        }

        const openCustomerOrderDetailModal = (id) => {
            $("#customerOrderDetailModal").modal('show');
        }

        const onPress = () => {
            if (keyword.key.length < 3) {
                return;
            }
            return getCustomer()
            
        }
        const showPermission = computed(() => window.userPermission)
        onMounted(()=> getCustomer())
        return {
            customers,
            openCustomerOrderModal,
            openCustomerOrderDetailModal,
            onPress,
            userOrders,
            getUserOrder,
            getCustomer,
            keyword,
            showPermission
        }
    }
}
</script>

<template>  
<div class="widget-content widget-content-area">
    <div class="row col-4">
        <input id="search" placeholder="Search By Name, Email or Phone" type="text" class="form-control form-control-sm"  @keyup.prevent="onPress" v-model="keyword.key" />
    </div>
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover mb-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Successful Payment</th>
                    <th class="text-center">Status</th>
                    <th v-if="showPermission.includes('customer-order-view')">Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(customer,index) in customers.data" :key="customer.id">
                    <tr>
                        <td>{{ index+1 }}</td>
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.address }}</td>
                        <td>{{ customer.order_details[0].successfull_paid }}</td>
                        <td class="text-center">{{ customer.status == 1 ? 'Active' : 'Deactive' }}</td>
                        <td v-if="showPermission.includes('customer-order-view')">
                            <a :href="keyword.url+'get-user/'+customer.id+'/orders'" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </a>
                        </td>
                    </tr>					
                </template>
            </tbody>
        </table>
            <Bootstrap4Pagination
                :data="customers"
                :limit="'3'"
                :keep-length="false"
                @pagination-change-page="getCustomer"
            />
    </div>
    <div id="customerOrderModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">All Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content widget-content-area">
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
                                    <template v-for="(order,index) in userOrders.data" :key="index">
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
                                :data="userOrders"
                                @pagination-change-page="getUserOrder"
                            />
                        </div>
            
                        
                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" ></i>Discard</button>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="customerOrderDetailModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content widget-content-area">
                        <div>
                            <table class="table table-bordered table-hover mb-4">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Name</th>
                                        <th>Picture</th>
                                        <th>Colour</th>
                                        <th>Size</th>
                                        <th>Fabric</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>tytret</td>
                                        <td>fghfgh</td>
                                        <td>
                                            rtssdg
                                        </td>
                                        <td>dgffgh</td>
                                        <td>fghfgh</td>
                                        <td>fghfghf</td>
                                        <td>fghhj</td>
                                        <td>3465</td>
                                        <td>dgh</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
            
                        
                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Discard</button>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
</template>