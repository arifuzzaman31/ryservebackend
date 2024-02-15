<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

export default {
    name: 'individualreport',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            customerData: [],
            filterdata : {
                from: '',
                to: '',
                order_state: ''
            },
            search: '',
            limit: 3,
            keepLength: false,
            url : baseUrl
        }
    },

    methods: {
        getIndividualCustomerReport(page = 1){
            axios.get(baseUrl+`get-individual-customer-report?page=${page}&keyword=${this.search}&byposition=${this.filterdata.order_state}&per_page=10&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.customerData = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },
        filterClear(){
            this.search = ''
            this.filterdata = {
                from: '',
                to: '',
                order_state: ''
            }
            this.getIndividualCustomerReport()
        },
        getSearch(){
            if(this.search.length < 3) return ;
            this.getIndividualCustomerReport()
        },
    },
    mounted(){
        this.getIndividualCustomerReport()
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
                        <h4>Individual Customer Report</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row mb-2">
                    <div class="col-md-3 col-lg-3 col-12">
                        <input type="text" v-model="search" @keyup="getSearch()" class="form-control form-control-sm" placeholder="Customer Name,Phone,Email">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getIndividualCustomerReport()" class="form-control form-control-sm" placeholder="End Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <select id="product-camp" class="form-control form-control-sm" @change="getIndividualCustomerReport()" v-model="filterdata.order_state">
                                <option value="">Choose...</option>
                                <option value="0">Pending</option>
                                <option value="1">Processing</option>
                                <option value="2">On Delivery</option>
                                <option value="3">Delivered</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-lg-2 col-12">
                        <button type="button" class="btn btn-danger" @click="filterClear()">CLEAR</button>
                    </div>
                </div>
                <div class="table-responsive" style=" overflow-x: auto">
                    <table class="table table-responsive table-bordered table-hover mb-4" style="overflow-x: auto">
                        <thead>
                            <tr>
                                <th>OrderID</th>
                                <th>Order Date</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Payment</th>
                                <th>Address</th>
                                <th>Order In Site</th>
                                <th>Spend Amount</th>
                                <th>Cancel Item</th>
                                <th>Refund</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-center">Payment Gateway</th>
                            </tr>
                        </thead>
                        <tbody v-if="customerData.data && customerData.data.length > 0">
                            <template v-for="(item,index) in customerData.data" :key="index">
                                <tr>
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.order_date }}</td>
                                    <td class="text-center">{{ item.user.name }}</td>
                                    <td class="text-center">{{ item.user.email }}</td>
                                    <td class="text-center">
                                            {{ item.user.phone }}
                                    </td>
                                    <td>{{ item.total_price }}</td>
                                    <td>{{ item.user.address }}</td>
                                    <td>
                                        <span v-if="item.order_position == 0">Pending</span>
                                        <span v-if="item.order_position == 1">Processing</span>
                                        <span v-if="item.order_position == 2">On Delivery</span>
                                        <span v-if="item.order_position == 3">Delivered</span>
                                    </td>
                                    <td>{{ formatPrice(item.total_price) }}</td>
                                    <td>{{ formatPrice(item.refund_count) }}</td>
                                    <td>{{ formatPrice(item.refunded_amount) }}</td>
                                    <td class="text-center">
                                        {{ item.payment_via == 0 ? 'COD' : 'Online' }}
                                    </td>
                                    <td>{{ item.payment_method_name }}</td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else class="text-center mt-3">
                            <tr>
                                <td colspan="13">No Order Found</td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                        <Bootstrap4Pagination
                            :data="customerData"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getIndividualCustomerReport"
                        />
                        <a target="_blank" :href="url+`get-individual-customer-report?excel=yes&date_from=${filterdata.from}&date_to=${filterdata.to}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>
<style>

</style>
