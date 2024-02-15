<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

export default {
    name: 'refundreport',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            refundData: [],
            filterdata : {
                from: '',
                to: '',
            },
            search: '',
            limit: 3,
            keepLength: false,
            url : baseUrl
        }
    },

    methods: {
        getCustomerRefundReport(page = 1){
            axios.get(baseUrl+`get-customer-refund-report?page=${page}&per_page=14&keyword=${this.search}&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.refundData = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },
        filterClear(){
            this.filterdata = {
                from: '',
                to: ''
            }
            this.search = ''
            this.getCustomerRefundReport()
        },
        getSearch(){
            if(this.search.length < 3) return ;
            this.getCustomerRefundReport()
        },
    },
    mounted(){
        this.getCustomerRefundReport()
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
                        <h4>Customer Refund Report</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row mb-2">
                    <div class="col-md-3 col-lg-3 col-12">
                        <input type="text" v-model="search" @keyup="getSearch()" class="form-control form-control-sm" placeholder="Customer Name,Phone,Email">
                    </div>
                    <div class="col-md-2 col-lg-3 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                    </div>
                    <div class="col-md-2 col-lg-3 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getCustomerRefundReport()" class="form-control form-control-sm" placeholder="End Date">
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
                                <th>Refund Request Date</th>
                                <th>Status</th>
                                <th>Approved/Reject Date</th>
                                <th>Refund Amount</th>
                                <th>Reason</th>
                                <th class="text-center">Payment Mode</th>
                                <th class="text-center">Payment Gateway</th>
                            </tr>
                        </thead>
                        <tbody v-if="refundData.data && refundData.data.length > 0">
                            <template v-for="(item,index) in refundData.data" :key="index">
                                <tr>
                                    <td>{{ item.order_id }}</td>
                                    <td>{{ item.order.order_date }}</td>
                                    <td class="text-center">{{ item.user.name }}</td>
                                    <td class="text-center">{{ item.user.email }}</td>
                                    <td class="text-center">{{ item.user.phone }}</td>
                                    <td>{{ item.order.total_price }}</td>
                                    <td>{{ item.user.address }}</td>
                                    <td>{{ item.refund_claim_date }}</td>
                                    <td>{{ item.is_refunded == 1 ? 'Approved' : item.is_refunded == 2 ? 'Rejected' : 'N/A' }}</td>
                                    <td>{{ item.refund_date }}</td>
                                    <td>{{ item.total_selling_price }}</td>
                                    <td>{{ item.is_refunded == 2 ? item.refund_reject_reason : 'N/A' }}</td>
                                    <td class="text-center">
                                        {{ item.order.payment_via == 0 ? 'COD' : 'Online' }}
                                    </td>
                                    <td>{{ item.order.payment_method_name }}</td>
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
                            :data="refundData"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getCustomerRefundReport"
                        />
                        <a target="_blank" :href="url+`get-customer-refund-report?excel=yes&date_from=${filterdata.from}&date_to=${filterdata.to}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>
<style>

</style>
