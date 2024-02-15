
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
            orders: [],
            search: '',
            filterdata : {
                payment_status: '',
                order_state: '',
                from: '',
                to: ''
            },
            limit: 3,
            keepLength: false,
            url : baseUrl
        }
    },

    methods: {
        getOrderReport(page = 1){
            axios.get(baseUrl+`get-order?page=${page}&per_page=10&keyword=${this.search}&byposition=${this.filterdata.order_state}&payment_status=${this.filterdata.payment_status}&from=${this.filterdata.from}&to=${this.filterdata.to}`)
            .then(result => {
                this.orders = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });  
        },
        filterClear(){
            this.search = ''
            this.filterdata = {
                payment_status : '',
                from: '',
                to: '',
                order_state: ''
            }
            this.getOrderReport()
        },
        getSearch(){
            if(this.search.length < 3) return ;
            this.getOrderReport()
        },
    },
    mounted(){
        this.getOrderReport()
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
                        <h4>Order Report</h4>
                    </div>                          
                </div>
            </div>       
            <div class="widget-content widget-content-area">
                <div class="row mb-2">
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.from" class="form-control form-control-sm" placeholder="Start Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <input type="text" onfocus="(this.type='date')" v-model="filterdata.to" @change="getOrderReport()" class="form-control form-control-sm" placeholder="End Date">
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <select id="product-camp" class="form-control form-control-sm" @change="getOrderReport()" v-model="filterdata.payment_status">
                            <option selected="" value="">Choose...</option>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                            <option value="2">Failed</option>
                            <option value="3">Cancel</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-lg-2 col-12">
                        <select id="product-camp" class="form-control form-control-sm" @change="getOrderReport()" v-model="filterdata.order_state">
                            <option selected="" value="">Choose...</option>
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
                                <th>SL</th>
                                <th>Order Date</th>
                                <th class="text-center">Items</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody v-if="orders.data && orders.data.length > 0">
                            <template v-for="(item,index) in orders.data" :key="index">
                                <tr>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ dateToString(item.order_date) }}</td>
                                    <td class="text-center"><strong>{{ item.total_item }}</strong></td>
                                    <td>
                                        <span v-if="item.payment_status == 0" class="badge outline-badge-warning">Unpaid</span>
                                        <span v-if="item.payment_status == 1" class="badge outline-badge-info">Paid</span>
                                        <span v-if="item.payment_status == 2" class="badge outline-badge-light">Failed</span>
                                        <span v-if="item.payment_status == 3" class="badge outline-badge-danger">Cancel</span>
                                    </td>
                                    <td>
                                        <span v-if="item.order_position == 0" class="badge outline-badge-info">Pending</span>
                                        <span v-if="item.order_position == 1" class="badge outline-badge-primary">Processing</span>
                                        <span v-if="item.order_position == 2" class="badge outline-badge-warning">On Delivery</span>
                                        <span v-if="item.order_position == 3" class="badge outline-badge-success">Delivered</span>
                                    </td>
                                    <td>{{ item.total_price }}</td>
                                </tr>					
                            </template>
                        </tbody>
                        <tbody v-else class="text-center mt-3">
                            <tr>
                                <td colspan="6">No Order Found</td>
                            </tr>
                                
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between">
                        <Bootstrap4Pagination
                            :data="orders"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getOrderReport"
                        />
                        </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
</template>
<style>

</style>				
