<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    name: 'order',
    props:["pickuppoint"],
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },

    data(){
        return {
            form: {
                progress_detail: [],
                status: true
            },
            orders: [],
            order_details: [],
            shipment_info: [],
            errors: [],
            order_id: '',
            single_order: null,
            order_status_id: '',
            order_status: {
                order_id: '',
                order_position: '',
                date: '',
                order_modify: '',
                payment_status: '',
                hasTrackingId: false,
                hub_name: ''
            },
            search: '',
            filterdata : {
                payment_status: '',
                order_state: '',
                status: ''
            },
            limit: 3,
            keepLength: false,
            url: baseUrl
        }
    },

    methods: {
        getOrder(page = 1){
            axios.get(baseUrl+`get-order?page=${page}&per_page=10&keyword=${this.search}&byposition=${this.filterdata.order_state}&payment_status=${this.filterdata.payment_status}&status=${this.filterdata.status}`)
            .then(result => {
                this.orders = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        getSearch(){
            if(this.search.length < 1) return ;
            this.getOrder()
        },

        cancelOrder(order){
            this.order_status.order_id = order.id
            this.order_status.order_modify = order.status
            $("#orderModifyModal").modal('show');
        },

        orderModify(){
            axios.post(baseUrl+`order/cancel`,this.order_status)
            .then(result => {
                this.order_status.order_id = ''
                this.order_status.order_modify = ''
                $("#orderModifyModal").modal('hide');
                this.successMessage(result.data)
                this.getOrder()
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        paymentStatus(order){
            this.order_status.order_id = order.id
            this.order_status.payment_status = order.payment_status
            $("#paymentStatusModal").modal('show');
        },

        paymentModify(id){
            axios.post(baseUrl+`update-payment-status/${id}`,this.order_status)
            .then(result => {
                this.order_status.order_id = ''
                this.order_status.payment_status = ''
                $("#paymentStatusModal").modal('hide');
                this.successMessage(result.data)
                this.getOrder()
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        updateStatus(){
            axios.post(baseUrl+`update/order/status`,this.order_status)
            .then(result => {
                $("#orderStatusModal").modal('hide');
                this.successMessage(result.data)
                this.getOrder()
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        deleteOrder(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "Order status will be Update!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl+'order/'+id).then(
                        response => {
                            this.formReset()
                            this.getOrder()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        getOrderdetail(id){
            axios.get(baseUrl+`orders-details/${id}`)
            .then(result => {
                this.order_details = result.data.details;
                this.shipment_info = result.data.order;
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        orderStatus(data){
            this.order_status.hasTrackingId = data.tracking_id ? true : false
            axios.get(baseUrl+`order-shipment/${data.id}`)
            .then(result => {
                this.order_status.order_id = result.data.id
                this.order_status.order_position = result.data.order_position
                $("#orderStatusModal").modal('show');
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        refundOrder(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "Do You Make Refund!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.get(baseUrl+`order-refund/${id}`).then(
                        response => {
                            // this.getOrder()
                            // console.log(response.data)
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        orderDetailModal(order) {

            axios.get(baseUrl+`orders-details/${order.id}`)
            .then(result => {
                this.order_id = order.id
                this.single_order = order
                this.order_status_id = order.order_position
                this.order_details = result.data.details;
                this.shipment_info = result.data.order;
                $("#orderDetailModal").modal('show');
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        formReset(){
            this.errors = '';
            this.search = '';
            this.form = {
                progress_detail : [],
                status : true
            }
            this.order_status = {
                order_id: '',
                order_position: '',
                date: '',
                order_modify: '',
                payment_status: '',
                hasTrackingId: false,
                hub_name: ''
            }

        },

        filterClear(){
            this.search = ''
            this.filterdata = {
                payment_status : '',
                status : '',
                order_state: ''
            }
            this.getOrder()
        },

    },

    mounted(){
        this.getOrder()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>
<style scoped>
#bar-progress {
    width: 100%;
    display: inline-flex;
    justify-content: center;
}

#bar-progress .step {
    display: inline-block;
}

#bar-progress .step .number-container {
    display: inline-block;
    border: solid 1px #000;
    border-radius: 50%;
    width: 24px;
    height: 24px;
}

#bar-progress .step.step-active .number-container {
    background-color: #000;
}

#bar-progress .step .number-container .number {
    font-weight: 700;
    font-size: .8em;
    line-height: 1.85em;
    display: block;
    text-align: center;
}

#bar-progress .step.step-active .number-container .number {
    color: white;
}

#bar-progress .step h5 {
    display: inline;
    font-weight: 100;
    font-size: .8em;
    margin-left: 10px;
    text-transform: uppercase;
}

#bar-progress .seperator {
    display: block;
    width: 20px;
    height: 1px;
    background-color: rgba(0, 0, 0, .2);
    margin: auto 20px;
}
</style>
<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Order</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row mb-2">
                        <div class="col-md-3 col-lg-4 col-12">
                            <input id="search" @keyup="getSearch()" placeholder="OrderID,Name,Phone,Email" type="text" class="form-control"  v-model="search" />
                        </div>

                        <div class="col-md-3 col-lg-2 col-12">
                            <select id="product-camp" class="form-control" @change="getOrder()" v-model="filterdata.order_state">
                                <option selected="" value="">Choose...</option>
                                <option value="0">Pending</option>
                                <option value="1">Processing</option>
                                <option value="2">On Delivery</option>
                                <option value="3">Delivered</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-2 col-12">
                            <select id="product-camp" class="form-control" @change="getOrder()" v-model="filterdata.status">
                                <option selected="" value="">Choose...</option>
                                <option value="1">Active</option>
                                <option value="0">Cancel</option>
                                <option value="2">On-Hold</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-2 col-12">
                            <select id="product-camp" class="form-control" @change="getOrder()" v-model="filterdata.payment_status">
                                <option selected="" value="">Choose...</option>
                                <option value="1">Paid</option>
                                <option value="0">Unpaid</option>
                                <option value="2">Failed</option>
                                <option value="3">Cancel</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-lg-2 col-12">
                            <button type="button" class="btn btn-danger" @click="filterClear()">CLEAR</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="min-height: 60vh;">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>OrderID</th>
                                    <th>Customer</th>
                                    <th>Price</th>
                                    <th>Shipping</th>
                                    <th>P-Type</th>
                                    <th>Payment</th>
                                    <th>PaymentBy</th>
                                    <!-- <th>Refund Claim Date</th> -->
                                    <th>Order Status</th>
                                    <th class="text-center">Progress</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="orders.data && orders.data.length > 0">
                                <template v-for="(order,index) in orders.data" :key="order.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ order.order_id }}</td>
                                        <td>{{
                                                order.user_shipping_info.first_name
                                            }} {{ order.user_shipping_info.last_name }}</td>
                                        <td>{{ order.total_price }}</td>
                                        <td>{{ order.shipping_amount }}</td>
                                        <td>
                                            <span v-if="order.payment_status == 0" class="badge badge-primary">COD</span>
                                            <span v-else class="badge badge-light">Others</span>
                                        </td>
                                        <td>
                                            <span v-if="order.payment_status == 0" class="badge badge-warning">Unpaid</span>
                                            <span v-if="order.payment_status == 1" class="badge badge-primary">Paid</span>
                                            <span v-if="order.payment_status == 2" class="badge badge-light">Failed</span>
                                            <span v-if="order.payment_status == 3" class="badge badge-danger">Cancel</span>
                                        </td>
                                        <td>{{ order.payment_method_name }}</td>
                                        <!-- <td>{{ order.refund_claim_date }}</td> -->
                                        <td>
                                            <span v-if="order.status == 0" class="badge badge-danger">Cancel</span>
                                            <span v-if="order.status == 1" class="badge badge-primary">Active</span>
                                            <span v-if="order.status == 2" class="badge badge-warning">On-Hold</span>
                                        </td>
                                        <td class="text-center">
                                            <span v-if="order.order_position == 0" class="badge badge-info">Pending</span>
                                            <span v-if="order.order_position == 1" class="badge badge-primary">Processing</span>
                                            <span v-if="order.order_position == 2" class="badge badge-warning">On Delivery</span>
                                            <span v-if="order.order_position == 3" class="badge badge-success">Delivered</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown custom-dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                    <!-- <a class="dropdown-item" @click="orderDetailModal(order)" href="javascript:void(0);">Order Details</a> -->
                                                    <a class="dropdown-item" :href="url+'order-details/'+order.id">Order Details</a>
                                                    <a class="dropdown-item" v-if="showPermission.includes('order-update')" @click="orderStatus(order)" href="javascript:void(0);">Progress</a>
                                                    <a class="dropdown-item" v-if="showPermission.includes('order-update')" @click="cancelOrder(order)" href="javascript:void(0);">Status</a>
                                                    <a class="dropdown-item" v-if="showPermission.includes('order-update')" @click="paymentStatus(order)" href="javascript:void(0);">Payment Status</a>
                                                    <a class="dropdown-item" v-if="(order.payment_status == 1) && (order.is_claim_refund == 1) && showPermission.includes('refund-action')" @click="refundOrder(order.id)" href="javascript:void(0);">Refund</a>
                                                    <a class="dropdown-item" v-if="showPermission.includes('order-delete')" @click="deleteOrder(order.id)" href="javascript:void(0);">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tbody v-else class="text-center mt-3">
                                <tr>
                                    <td colspan="12">No Order Found</td>
                                </tr>

                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="orders"
                                :limit="limit"
                                :keep-length="keepLength"
                                @pagination-change-page="getOrder"
                            />
                    </div>
                    <div class="float-right">
                        <a target="_blank" :href="url+`get-order?excel=yes&keyword=${search}&byposition=${filterdata.order_state}&status=${filterdata.status}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="orderDetailModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body" v-if="order_id">

                        <div class="widget-content widget-content-area">
                            <div class="row d-flex justify-content-between">
                                <div class="col-md-4 text-left" v-if="order.payment_via == 1">
                                    <h6 class="text-success">Billing Info</h6>
                                    <p>Name: {{ order.user_billing_info.first_name }} {{ shipment_info.user_billing_info.last_name }}</p>
                                    <p>Phone: {{ shipment_info.user_billing_info.phone }}</p>
                                    <p>Email: {{ shipment_info.user_billing_info.email }}</p>
                                    <p>Street Address: {{ shipment_info.user_billing_info.street_address }}</p>
                                    <p>Post Code: {{ shipment_info.user_billing_info.post_code }}</p>
                                    <p>City: {{ shipment_info.user_billing_info.city }}</p>
                                    <p>Country: {{ shipment_info.user_billing_info.country }}</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <h6 class="text-success">Shipping Info</h6>
                                    <p>Name: {{ shipment_info.user_shipping_info.last_name }}</p>
                                    <p>Phone: {{ shipment_info.user_shipping_info.phone }}</p>
                                    <p>Email: {{ shipment_info.user_shipping_info.email }}</p>
                                    <p>Street Address: {{ shipment_info.user_shipping_info.street_address }}</p>
                                    <p>Post Code: {{ shipment_info.user_shipping_info.post_code }}</p>
                                    <p>City: {{ shipment_info.user_shipping_info.city }}</p>
                                    <p>Country: {{ shipment_info.user_shipping_info.country }}</p>
                                </div>
                                <!-- <div class="col-md-4 text-right" v-else>
                                    <h6 class="text-success">Shipping Info</h6>
                                    <p class="text-info">As Per Billing Info</p>
                                </div> -->
                            </div>
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
                                        <tr v-for="(orderdetail,index) in order_details" :key="index">
                                            <td>{{ index+1 }}</td>
                                            <td>{{ orderdetail.product.product_name }}</td>
                                            <td>
                                                <img height="60" :src="orderdetail.product.product_image" />
                                            </td>
                                            <td>{{ orderdetail.colour.color_name }}</td>
                                            <td>{{ orderdetail.size.size_name }}</td>
                                            <td>{{ orderdetail.fabric.fabric_name }}</td>
                                            <td>{{ orderdetail.selling_price }}</td>
                                            <td>{{ orderdetail.quantity }}</td>
                                            <td>{{ orderdetail.total_selling_price }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"  @click="formReset"></i>Discard</button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="orderStatusModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content">
                            <form method="post">
                                <div>
                                    <div class="form-group">
                                        <label for="">Order Status</label>
                                        <select class="form-control" v-model="order_status.order_position">
                                            <option value="0">Pending</option>
                                            <option value="1">Processing</option>
                                            <option value="2">Ready To Delivery</option>
                                            <option value="3">Delivered</option>
                                        </select>
                                    </div>
                                    <div class="form-group"  v-if="(order_status.order_position == 1) && (order_status.hasTrackingId == false)">
                                        <label for="">Pickup Point</label>
                                        <select class="form-control" v-model="order_status.hub_name">
                                            <option value="">Choose One</option>
                                            <option v-for="point in pickuppoint" :key="point.id" :value="point">{{ point.hub_name }}-{{ point.hub_code }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Date</label>
                                        <input id="date" v-model="order_status.date" class="form-control flatpickr-input active" type="date" placeholder="Select Date..">
                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click="updateStatus(order_status.order_id)" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="orderModifyModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content">
                            <form method="post">
                                <div>
                                    <div class="form-group">
                                        <label for="">Order</label>
                                        <select class="form-control" v-model="order_status.order_modify">
                                            <option value="0">Cancel</option>
                                            <option value="1">Active</option>
                                            <option value="2">On-Hold</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click="orderModify(order_status.order_id)" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="paymentStatusModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content">
                            <form method="post">
                                <div>
                                    <div class="form-group">
                                        <label for="">Payment</label>
                                        <select class="form-control" v-model="order_status.payment_status">
                                            <option value="1">Paid</option>
                                            <option value="0">Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click="paymentModify(order_status.order_id)" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
