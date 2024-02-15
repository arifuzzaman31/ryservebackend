<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

export default {
    name: 'report',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            paymentData: [],
            filterdata : {
                payment_status : '',
                from: '',
                to: ''
            },
            limit: 3,
            keepLength: false,
            url : baseUrl
        }
    },

    methods: {
        getPaymentReport(page = 1){
            axios.get(baseUrl+`get-payment-report?page=${page}&per_page=14&payment_status=${this.filterdata.payment_status}&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.paymentData = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },
        filterClear(){
            this.filterdata = {
                payment_status : '',
                from: '',
                to: ''
            }
            this.getPaymentReport()
        }
    },
    mounted(){
        this.getPaymentReport()
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
                        <h4>Payment Report</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row mb-2">
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getPaymentReport()" class="form-control form-control-sm" placeholder="End Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <select id="product-camp" class="form-control form-control-sm" @change="getPaymentReport()" v-model="filterdata.payment_status">
                            <option value="">Choose...</option>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>OrderID</th>
                                    <th>Order Date</th>
                                    <th class="text-center">Customer</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Payment</th>
                                    <th>Address</th>
                                    <th>Amount</th>
                                    <th class="text-center">Payment Gateway</th>
                                </tr>
                            </thead>
                            <tbody v-if="paymentData.data && paymentData.data.length > 0">
                                <template v-for="(item,index) in paymentData.data" :key="index">
                                    <tr>
                                        <td>{{ item.order_id }}</td>
                                        <td>{{ item.order_date }}</td>
                                        <td class="text-center">{{ item.user.name }}</td>
                                        <td class="text-center">{{ item.user.email }}</td>
                                        <td class="text-center">
                                                {{ item.user.phone }}
                                        </td>
                                        <td>{{ formatPrice(item.total_price) }}</td>
                                        <td>{{ item.user.address }}</td>
                                        <td>{{ formatPrice(item.total_price) }}</td>
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
                            :data="paymentData"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getPaymentReport"
                            />
                            <a target="_blank" :href="url+`get-payment-report?paymentexcel=yes&date_from=${filterdata.from}&date_to=${filterdata.to}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>
                        </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>
<style>

</style>
