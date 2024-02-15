<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    name: 'refund',
    props:['pagefrom','pagetitle'],
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },

    data(){
        return {
            refund_items: [],
            order_details: [],
            shipment_info: [],
            errors: [],
            order_id: '',
            single_order: null,
            order_status_id: '',
            search: '',
            filterdata : {
                refund_status: ''
            },
            refund:{
                id: '',
                refund_status: '',
                reason: ''
            },
            limit: 3,
            keepLength: false,
            url: baseUrl
        }
    },

    methods: {
        getRefundItem(page = 1){
            axios.get(baseUrl+`refund-item-detail?from=${this.pagefrom}&page=${page}&per_page=10&keyword=${this.search}`)
            .then(result => {
                this.refund_items = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });  
        },

        getSearch(){
            if(this.search.length < 3) return ;
            this.getRefundItem()
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

        refundOrder(item){
            this.refund.id = item.id
            // this.refund.refund_status = order.is_refunded
            $("#refundModifyModal").modal('show');
        },

        refundModify(){
            Swal.fire({
                title: 'Are you sure?',
                text: "Refund Status Will be Changed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(baseUrl+`order-item-refund`,this.refund).then(
                        response => {
                            $("#refundModifyModal").modal('hide');
                            this.getRefundItem()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            }) 
        },

        formReset(){
            this.errors = '';
            this.search = '';
            
        },

        filterClear(){
            this.search = ''
            this.filterdata = {
                refund_state: ''
            }
            this.getRefundItem()
        },

    },

    mounted(){
        this.getRefundItem()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
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
                            <h4>{{ pagetitle }}</h4>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="row mb-2">
                        <div class="col-md-3 col-lg-3 col-12">
                            <input id="search" @keyup="getSearch()" placeholder="Search By OrderID" type="text" class="form-control"  v-model="search" />
                        </div>
                        
                        <div class="col-md-2 col-lg-2 col-12">
                            <button type="button" class="btn btn-danger" @click="filterClear()">CLEAR</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="min-height: 60vh;">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Order Code</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Payment</th>
                                    <th v-if="(pagefrom == 'request-refund') && showPermission.includes('refund-action')" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="refund_items.data && refund_items.data.length > 0">
                                <template v-for="(item,index) in refund_items.data" :key="index">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ item.user.name }}</td>
                                        <td>{{ '#G-Store:'+item.order_id }}</td>
                                        <td>{{ item.product.product_name }}</td>
                                        <td>{{ item.selling_price }}</td>
                                        <td>{{ item.order.payment_status == 1 ? 'Paid' : 'Unpaid' }}</td>
                                        <td class="text-center" v-if="(pagefrom == 'request-refund') && showPermission.includes('refund-action')">
                                            <div class="dropdown custom-dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                    <a class="dropdown-item" @click="refundOrder(item)" href="javascript:void(0);">Refund</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                            <tbody v-else class="text-center mt-3">
                                <tr>
                                    <td colspan="7">No Refund Found</td>
                                </tr>
                                 
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="refund_items"
                                :limit="limit"
                                :keep-length="keepLength"
                                @pagination-change-page="getRefundItem"
                            />
                    </div>
                   
                </div>
            </div>
        </div>

        <div id="refundModifyModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Refund</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content">
                            <form method="post">
                                <div>
                                    <div class="form-group">
                                        <label for="">Refund Action</label>
                                        <select class="form-control" v-model="refund.refund_status">
                                            <option value="">Select Action</option>
                                            <option value="1">Approve</option>
                                            <option value="2">Reject</option>
                                        </select>
                                    </div>
                                    <div class="form-group" v-if="refund.refund_status == 2">
                                        <label for="">Reject Reason</label>
                                        <textarea v-model="refund.reason" class="form-control" rows="3"></textarea>
                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click.prevent="refundModify()" class="btn btn-primary">Update</button>
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