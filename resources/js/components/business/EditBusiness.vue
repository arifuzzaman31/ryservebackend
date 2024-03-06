<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    props:['businessData'],
    components: {
        QuillEditor
    },
    data(){
        return {
            business : {
                id: '',
                business_name: '',
                short_description: '',
                long_description: '',
                business_type: '',
                service_type: '',
                business_category: '',
                country: '',
                city: '',
                location_point: '',
                business_owner: '',
                business_manager: '',
                number_of_employee: '',
                trade_licence: '',
                tin: '',
                bin: '',
                status: 'true'
            },
            validation_error: {},
        }
    },
    methods:{
            setupData(){
            // if(this.businessData){
                this.business.id = this.businessData.id
                this.business.business_name = this.businessData.businessName
                this.business.short_description = this.businessData.shortDescription
                this.business.long_description = this.businessData.longDescription
                this.business.business_type = this.businessData.businessType
                this.business.service_type = this.businessData.serviceType
                this.business.business_category = this.businessData.businessCategory
                this.business.country = this.businessData.country
                this.business.city = this.businessData.city
                this.business.location_point = this.businessData.locationPoint
                this.business.business_owner = this.businessData.businessOwner
                this.business.business_manager = this.businessData.businessManager
                this.business.number_of_employee = this.businessData.numberOfEmployee
                this.business.trade_licence = this.businessData.tradeLicence
                this.business.tin = this.businessData.tin
                this.business.bin = this.businessData.bin
                this.business.status = this.businessData.status
            // }
        },
    },
    mounted(){
    }
}
</script>
<style scoped>
.modal-xl{
    max-width: 86% !important;
}
</style>
<template>
    <div class="row">
        <div id="updateBusinessModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{ businessData.businessName }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" @submit.prevent="updateBusiness()" id="add-product-form">
                            <div class="row">
                                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                                    <div class="statbox widget box ">
                                        <div class="widget-content ">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <label for="Business-name">Business name</label>
                                                    <input type="text" class="form-control" :class="validation_error.hasOwnProperty('business_name') ? 'is-invalid' : ''" id="Business-name" placeholder="Business name" v-model="business.business_name" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('business_name')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.business_name[0] }}
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="business_type">Business Type</label>
                                                    <select id="business_type" class="form-control" v-model="business.business_type">
                                                        <option value="">Choose Business Type...</option>
                                                        <option value="PROPIETOR">PROPIETOR</option>
                                                        <option value="PARTNERSHIP">PARTNERSHIP</option>
                                                        <option value="LTD_COMPANY">LTD COMPANY</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                    <div
                                                        v-if="validation_error.hasOwnProperty('business_type')"
                                                        class="text-danger font-weight-bold"
                                                    >
                                                        {{ validation_error.business_type[0] }}
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="service_type">Service Type</label>
                                                    <select id="service_type" class="form-control" v-model="business.service_type">
                                                        <option value="">Choose Service Type...</option>
                                                        <option value="TABLE_RESERVATION">TABLE RESERVATION</option>
                                                        <option value="ROOM_RESERVATION">ROOM RESERVATION</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                    <div
                                                        v-if="validation_error.hasOwnProperty('business_type')"
                                                        class="text-danger font-weight-bold"
                                                    >
                                                        {{ validation_error.business_type[0] }}
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="business_category">Business Category</label>
                                                    <select id="business_category" class="form-control" v-model="business.business_category">
                                                        <option value="">Choose Business Category...</option>
                                                        <option value="HOTEL">HOTEL</option>
                                                        <option value="RESTAURANT">RESTAURANT</option>
                                                        <option value="SERVICE_APARTMENT">SERVICE APARTMENT</option>
                                                        <option value="MOVIE_THEATER">MOVIE THEATER</option>
                                                        <option value="SPA">SPA</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                    <div
                                                        v-if="validation_error.hasOwnProperty('business_type')"
                                                        class="text-danger font-weight-bold"
                                                    >
                                                        {{ validation_error.business_type[0] }}
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="form-row mt-1">
                                                    <div id="tooltips" class="col-lg-12  col-md-12">
                                                        <div class="widget-content ">
                                                            <label for="editor-container">Short Description</label>
                                                            <QuillEditor theme="snow" v-model:content="business.short_description" contentType="html" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row mt-1">
                                                    <div id="tooltips" class="col-lg-12 col-md-12">
                                                        <div class="widget-content ">
                                                            <label for="editor-container">Long Description</label>
                                                            <QuillEditor theme="snow" v-model:content="business.long_description" contentType="html" />
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                                    <div class="statbox widget box ">
                                        <div class="widget-content ">
                                            <div class="form-row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="country">Country</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('country') ? 'is-invalid' : ''" id="country" placeholder="Country" v-model="business.country">
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('country')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.country[0] }}
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-4 mb-3">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('city') ? 'is-invalid' : ''" id="ity" placeholder="City" v-model="business.city" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('city')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.city[0] }}
                                                        </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="location_point">Location Point</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('location_point') ? 'is-invalid' : ''" id="location_point" placeholder="google Location Link" v-model="business.location_point" >
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('location_point')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.location_point[0] }}
                                                        </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="BusinessOwner">Business Owner</label>
                                                    <input type="text" class="form-control form-control-sm" id="BusinessOwner" placeholder="Business Owner Info: name, phone, address" v-model="business.business_owner" />
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="business_manager">Business Manager</label>
                                                    <input type="text" class="form-control form-control-sm" id="business_manager" placeholder="Business Manager: name, phone, address" v-model="business.business_manager" />
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="tin">Tin</label>
                                                    <input type="text" class="form-control form-control-sm" id="tin" placeholder="TIN Number" v-model="business.tin" />
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="bin">Bin</label>
                                                    <input type="text" class="form-control form-control-sm" id="bin" placeholder="BIN Number" v-model="business.bin" />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="number_of_employee">Number Of Employee</label>
                                                    <input type="number" class="form-control form-control-sm" id="number_of_employee" placeholder="Total Employee" v-model="business.number_of_employee" />
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="trade_licence">Trade Licence</label>
                                                    <input type="text" class="form-control form-control-sm" id="trade_licence" placeholder="Enter Trade Licence" v-model="business.trade_licence" />
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="status">Status</label>
                                                    <select id="status" class="form-control" v-model="business.status">
                                                        <option value="true">Active</option>
                                                        <option value="false">Deactive</option>
                                                    </select>
                                                    <div
                                                        v-if="validation_error.hasOwnProperty('status')"
                                                        class="text-danger font-weight-bold"
                                                    >
                                                        {{ validation_error.status[0] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-info mt-1 btn-lg" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
