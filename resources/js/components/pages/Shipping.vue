<script>
import axios from 'axios';
import Mixin from '../../mixer';
import country from '../../country.js';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    name: 'shipping',
    mixins: [Mixin],
    components:{
        Bootstrap4Pagination
    },

    data(){
        return {
            shipping_infos: [],
            order_details: [],
            shipment_info: [],
            validation_error : {},
            order_id: '',
            single_order: null,
            order_status_id: '',
            search: '',
            filterdata : {
                refund_status: ''
            },
            countries: null,
            charge : {
                id: '',
                amount: 0,
                country_name: '',
                inside_city_pathao: 0,
                inside_city_e_courier: 0,
                outside_city_pathao: 0,
                outside_city_e_courier: 0,
                status: 1
            },
            url: baseUrl
        }
    },

    methods: {
        getShippingData(page = 1){
            axios.get(baseUrl+`get-shipping-data?page=${page}&per_page=12&keyword=${this.search}`)
            .then(result => {
                this.shipping_infos = result.data;
            })
            .catch(errors => {
                console.log(errors);
            });
        },

        getSearch(){
            if(this.search.length < 3) return ;
            this.getShippingData()
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

        editShipping(item){
            this.formReset()
            this.charge.id = item.id
            this.charge.country_name = item.country_name+' code '+item.country_code
            // if(item.country_name == 'Bangladesh'){
            //     let dt = JSON.parse(item.shipping_charge);
            //     this.charge.inside_city_pathao = dt['inside_city']['pathao']
            //     this.charge.inside_city_e_courier = dt['inside_city']['e_courier']
            //     this.charge.outside_city_pathao = dt['outside_city']['pathao']
            //     this.charge.outside_city_e_courier = dt['outside_city']['e_courier']
            // }else{
            //     this.charge.amount = JSON.parse(item.shipping_charge)['amount']
            // }

            this.charge.status = item.status
            $("#addShippingModal").modal('show');
        },

        setCode(){

        },

        addShppingCharge(){
            axios.post(baseUrl+`add-shipping-charge`,this.charge).then(
                response => {
                    $("#addShippingModal").modal('hide');
                    this.getShippingData()
                    this.successMessage(response.data)
                    // console.log(response.data)
                }
            ). catch(error => {
                if(error.response.status == 422){
                        this.validation_error = error.response.data.errors;
                    }
            })
        },

        deleteShipping(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl+`remove-shipping-data/${id}`).then(
                        response => {
                            this.getShippingData()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        this.validationError()
                    })
                }
            })

        },


        formReset(){
            this.validation_error = {};
            this.search = '';
            this.charge = {
                id: '',
                amount: 0,
                country_name: '',
                inside_city_pathao: 0,
                inside_city_e_courier: 0,
                outside_city_pathao: 0,
                outside_city_e_courier: 0,
                status: 1
            }

        },

        filterClear(){
            this.search = ''
            this.filterdata = {
                refund_state: ''
            }
            this.getShippingData()
        },

    },

    mounted(){
        this.countries = country
        this.getShippingData()
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
                            <h4>Shipping Charge</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('add-shipping')" data-toggle="modal" data-target="#addShippingModal" @click="formReset">Add New</button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row mb-2">
                        <div class="col-md-3 col-lg-3 col-12">
                            <input id="search" @keyup="getSearch()" placeholder="Search By Country Name" type="text" class="form-control"  v-model="search" />
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
                                    <th>Country</th>
                                    <th>Code</th>
                                    <!-- <th>Amount</th> -->
                                    <th>status</th>
                                    <th v-if="showPermission.includes('delete-shipping') && showPermission.includes('edit-shipping')" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="shipping_infos.data && shipping_infos.data.length > 0">
                                <template v-for="(item,index) in shipping_infos.data" :key="index">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ item.country_name }}</td>
                                        <td>{{ item.country_code }}</td>
                                        <!-- <td>
                                            <p v-if="item.country_name == 'Bangladesh'">
                                                Inside City: Pathao={{ JSON.parse(item.shipping_charge)['inside_city']['pathao'] }}, E-courier=Pathao={{ JSON.parse(item.shipping_charge)['inside_city']['e_courier'] }}
                                                <br>Outside City: Pathao={{ JSON.parse(item.shipping_charge)['outside_city']['pathao'] }}, E-courier={{ JSON.parse(item.shipping_charge)['outside_city']['e_courier'] }}
                                            </p>
                                            <p v-else> {{ JSON.parse(item.shipping_charge)['amount'] }}</p>
                                        </td> -->
                                        <td>{{ item.status == 1 ? 'Active' : 'Deactive' }}</td>
                                        <td class="text-center" v-if="showPermission.includes('delete-shipping') && showPermission.includes('edit-shipping')">
                                            <button type="button" v-if="showPermission.includes('edit-shipping')" class="btn btn-sm btn-info" @click="editShipping(item)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('delete-shipping')" class="btn btn-sm btn-danger ml-2" @click="deleteShipping(item.id)">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tbody v-else class="text-center mt-3">
                                <tr>
                                    <td colspan="7">No Data Found</td>
                                </tr>

                            </tbody>
                        </table>
                        <Bootstrap4Pagination
                                :data="shipping_infos"
                                :limit="'1'"
                                :keep-length="'false'"
                                @pagination-change-page="getShippingData"
                            />
                    </div>

                </div>
            </div>
        </div>

        <div id="addShippingModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-3">Add Shipping Charge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content widget-content-area">
                            <form>
                                <div>
                                    <div class="form-group">
                                        <label for="">Select Country</label>
                                        <select class="form-control" v-model="charge.country_name" v-if="charge.id == ''">
                                            <option value="">Select Action</option>

                                            <option :value="cunt.name+' code '+cunt.code" v-for="cunt in countries" :key="cunt.code">{{ cunt.name }} ({{ cunt.code }})</option>

                                        </select>
                                        <input type="text" class="form-control" v-model="charge.country_name" v-else disabled />
                                        <span
                                        v-if="validation_error.hasOwnProperty('country_name')"
                                        class="text-danger"
                                    >
                                        {{ validation_error.country_name[0] }}
                                    </span>
                                    </div>
                                    <!-- <div class="form-group" v-if="charge.country_name !== 'Bangladesh code BD'">
                                        <label for="amount">Amount</label>
                                        <input type="number" v-model="charge.amount" class="form-control" id="amount" />
                                    </div>
                                    <p v-if="charge.country_name == 'Bangladesh code BD'" class="text-bold mt-2">Inside Capital</p>
                                    <div class="d-flex justify-content-between" v-if="charge.country_name == 'Bangladesh code BD'">
                                        <div class="form-group">
                                            <label for="inside_citypathao">Amount (Pathao)</label>
                                            <input type="number" v-model="charge.inside_city_pathao" class="form-control" id="inside_citypathao" />
                                        </div>
                                        <div class="form-group">
                                            <label for="inside_citye_courier">Amount (E-Courier)</label>
                                            <input type="number" v-model="charge.inside_city_e_courier" class="form-control" id="inside_citye_courier" />
                                        </div>
                                    </div>
                                    <p class="text-bold mt-2" v-if="charge.country_name == 'Bangladesh code BD'">Outside Capital</p>
                                    <div class="d-flex justify-content-between" v-if="charge.country_name == 'Bangladesh code BD'">
                                        <div class="form-group">
                                            <label for="outside_citypathao">Amount (Pathao)</label>
                                            <input type="number" v-model="charge.outside_city_pathao" class="form-control" id="outside_citypathao" />
                                        </div>
                                        <div class="form-group">
                                            <label for="outside_citye_courier">Amount (E-Courier)</label>
                                            <input type="number" v-model="charge.outside_city_e_courier" class="form-control" id="outside_citye_courier" />
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <select class="form-control" v-model="charge.status">
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>

                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn btn-default" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click.prevent="addShppingCharge()" class="btn btn-primary">{{ charge.id == '' ? 'Add' : 'Update' }}</button>
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
