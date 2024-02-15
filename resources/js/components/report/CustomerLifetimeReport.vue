<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

export default {
    name: 'lifetimereport',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            lifetimeData: [],
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
        getCustomerLifetimeReport(page = 1){
            axios.get(baseUrl+`get-customer-lifetime-report?page=${page}&per_page=12&keyword=${this.search}&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.lifetimeData = result.data;
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
            this.getCustomerLifetimeReport()
        },
        getSearch(){
            if(this.search.length < 3) return ;
            this.getCustomerLifetimeReport()
        },
    },
    mounted(){
        this.getCustomerLifetimeReport()
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
                        <h4>Customer Life Time Value Report</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row mb-2">
                    <div class="col-md-4 col-lg-4 col-12">
                        <input type="text" v-model="search" @keyup="getSearch()" class="form-control form-control-sm" placeholder="Customer Name,Phone,Email">
                    </div>
                    <div class="col-md-3 col-lg-3 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                    </div>
                    <div class="col-md-3 col-lg-3 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getCustomerLifetimeReport()" class="form-control form-control-sm" placeholder="End Date">
                    </div>

                    <div class="col-md-2 col-lg-2 col-12">
                        <button type="button" class="btn btn-danger" @click="filterClear()">CLEAR</button>
                    </div>
                </div>
                <div class="table-responsive" style=" overflow-x: auto">
                    <table class="table table-responsive table-bordered table-hover mb-4" style="overflow-x: auto">
                        <thead>
                            <tr>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Payment</th>
                                <th>Address</th>
                                <th>Total Order in Aranya</th>
                                <th>Total Amount Spent</th>
                                <th>Total Item Cancel</th>
                                <th>Total Refund</th>
                                <th class="text-center">Total Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody v-if="lifetimeData.data && lifetimeData.data.length > 0">
                            <template v-for="(item,index) in lifetimeData.data" :key="index">
                                <tr>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.email }}</td>
                                    <td class="text-center">{{ item.phone }}</td>
                                    <td>{{ item.total_spent_amount }}</td>
                                    <td>{{ item.address }}</td>
                                    <td>{{ item.count_order }}</td>
                                    <td>{{ item.total_spent_amount }}</td>
                                    <td>{{ item.cancel_count }}</td>
                                    <td class="text-center">
                                        {{ item.refund_count }}
                                    </td>
                                    <td>{{ item.refunded_amount }}</td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else class="text-center mt-3">
                            <tr>
                                <td colspan="12">No Order Found</td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                        <Bootstrap4Pagination
                            :data="lifetimeData"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getCustomerLifetimeReport"
                        />
                        <a target="_blank" :href="url+`get-customer-lifetime-report?excel=yes&date_from=${filterdata.from}&date_to=${filterdata.to}`" type="button" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>  Excel</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</template>
