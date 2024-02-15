<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    name: 'invoice',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },

    data(){
        return {
            orders: [],
            filterdata : {
                payment_status: '',
                order_state: '',
                status: '',
                from: '',
                to: ''
            },
            search: '',
            limit: 3,
            keepLength: false,
            url: baseUrl
        }
    },

    methods: {
        getOrder(page = 1){
            axios.get(baseUrl+`get-order?page=${page}&per_page=10&keyword=${this.search}&byposition=${this.filterdata.order_state}&payment_status=${this.filterdata.payment_status}&status=${this.filterdata.status}&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.orders = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        formReset(){
            this.errors = '';
            this.form = {
                progress_detail : [],
                status : true
            }

        },

        filterClear(){
            this.search = ''
            this.filterdata = {
                payment_status : '',
                status : '',
                order_state: '',
                from: '',
                to: ''
            }
            this.getOrder()
        },
        getSearch(){
            if(this.search.length < 3) return ;
            this.getOrder()
        },

    },

    mounted(){
        this.getOrder()
    }
}
</script>
<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Invoice Report</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row mb-2">
                        <div class="col-md-3 col-lg-3 col-12">
                            <input type="text" v-model="search" @keyup="getSearch()" class="form-control form-control-sm" placeholder="OrderID,Name,Phone,Email">
                        </div>

                        <div class="col-md-3 col-lg-3 col-12">
                            <select id="product-camp" class="form-control  form-control-sm" @change="getOrder()" v-model="filterdata.order_state">
                                <option selected="" value="">Choose...</option>
                                <option value="0">Pending</option>
                                <option value="1">Processing</option>
                                <option value="2">On Delivery</option>
                                <option value="3">Delivered</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-3 col-12">
                            <select id="product-camp" class="form-control  form-control-sm" @change="getOrder()" v-model="filterdata.status">
                                <option selected="" value="">Choose...</option>
                                <option value="1">Active</option>
                                <option value="0">Cancel</option>
                                <option value="2">On-Hold</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-3 col-12">
                            <select id="product-camp" class="form-control  form-control-sm" @change="getOrder()" v-model="filterdata.payment_status">
                                <option selected="" value="">Choose...</option>
                                <option value="1">Paid</option>
                                <option value="0">Unpaid</option>
                                <option value="2">Failed</option>
                                <option value="3">Cancel</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-3 col-12 mt-1">
                            <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                        </div>
                        <div class="col-md-3 col-lg-3 col-12 mt-1">
                            <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getOrder()" class="form-control form-control-sm" placeholder="End Date">
                        </div>

                        <div class="col-md-2 col-lg-1 col-12 mt-1">
                            <button type="button" class="btn btn-danger" @click="filterClear()">CLEAR</button>
                        </div>
                    </div>
                    <div class="table-responsive" style=" overflow-x: auto">
                        <table class="table table-bordered table-hover mb-4" style="overflow-x: auto">
                            <thead>
                                <tr>
                                    <th>OrderID</th>
                                    <th>Order Date</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Delivery</th>
                                    <th>Item Qty</th>
                                    <th>Amount</th>
                                    <th>Profit</th>
                                    <th class="text-center">Payment Method</th>
                                    <th class="text-center">Payment Gateway</th>
                                </tr>
                            </thead>
                            <tbody v-if="orders.data && orders.data.length > 0">
                                <template v-for="(order,index) in orders.data" :key="index">
                                    <tr>
                                        <td>{{ order.id }}</td>
                                        <td>{{ dateToString(order.order_date) }}</td>
                                        <td>{{
                                                order.user_shipping_info.first_name
                                            }} {{ order.user_shipping_info.last_name }}</td>
                                        <td>{{ order.user_shipping_info.phone }}</td>
                                        <td>{{ order.user_shipping_info.email }}</td>
                                        <td>{{ order.user_shipping_info.street_address }}</td>
                                        <td class="text-center">
                                            <span v-if="order.order_position == 0">Pending</span>
                                            <span v-if="order.order_position == 1">Processing</span>
                                            <span v-if="order.order_position == 2">On Delivery</span>
                                            <span v-if="order.order_position == 3">Delivered</span>
                                        </td>
                                        <td>{{ order.total_item }}</td>
                                        <td>{{ formatPrice(order.total_price) }}</td>
                                        <td>{{ formatPrice(order.total_price - order.buying_sum) }}</td>
                                        <td class="text-center">
                                            {{ order.payment_via == 0 ? 'COD' : 'Online' }}
                                        </td>
                                        <td>{{ order.payment_method_name }}</td>
                                    </tr>
                                </template>
                            </tbody>
                            <tbody v-else class="text-center mt-3">
                                <tr>
                                    <td colspan="12">No Data Found</td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <Bootstrap4Pagination
                                :data="orders"
                                :limit="limit"
                                :keep-length="keepLength"
                                @pagination-change-page="getOrder"
                            />

                            <a target="_blank" :href="url+`get-order?invoicexcel=yes&byposition=${filterdata.order_state}&payment_status=${filterdata.payment_status}&status=${filterdata.status}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
