<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    components: {
        QuillEditor
    },

    data(){
        return {
            business : {
                businessName: '',
                shortDescription: '',
                longDescription: '',
                businessType: '',
                serviceType: '',
                businessCategory: '',
                country: '',
                city: '',
                locationPoint: '',
                businessOwner: '',
                businessManager: '',
                numberOfEmployee: '',
                tradeLicence: '',
                tin: '',
                bin: '',
                status: 'true'
            },
            isSubmiting:false,
            validation_error: {},
        }
    },
    methods: {
        async createBusiness(){
            this.isSubmiting = true
            const token = await this.getUserToken()
            await axios.post(`${apiUrl}backendapi/business`, this.business, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((result) => {
                    console.log(result)
                    this.successMessage({status:'success',message:'New Business Created Successful'})
                    // window.location.href = baseUrl+'business'
                    this.isSubmiting = false
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },



        clearForm() {
            this.business = {
                businessName: '',
                shortDescription: '',
                longDescription: '',
                businessType: '',
                serviceType: '',
                businessCategory: '',
                country: '',
                city: '',
                locationPoint: '',
                businessOwner: '',
                businessManager: '',
                numberOfEmployee: '',
                tradeLicence: '',
                tin: '',
                bin: '',
                status: 'true'
            }
            this.isSubmiting = false
            this.validation_error = {}

        },
    },
    mounted(){
    }
}
</script>
<template>
    <form class="needs-validation" method="post" @submit.prevent="createBusiness()" id="add-product-form">
        <div class="row">

            <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                <h4>Create New Business</h4>
            </div>

            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="Business-name">Business name</label>
                                <input type="text" class="form-control" :class="validation_error.hasOwnProperty('businessName') ? 'is-invalid' : ''" id="Business-name" placeholder="Business name" v-model="business.businessName" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('businessName')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.businessName[0] }}
                                    </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="business_type">Business Type</label>
                                <select id="business_type" class="form-control" v-model="business.businessType">
                                    <option value="">Choose Business Type...</option>
                                    <option value="PROPIETOR">PROPIETOR</option>
                                    <option value="PARTNERSHIP">PARTNERSHIP</option>
                                    <option value="LTD_COMPANY">LTD COMPANY</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('businessType')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.businessType[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="service_type">Service Type</label>
                                <select id="service_type" class="form-control" v-model="business.serviceType">
                                    <option value="">Choose Service Type...</option>
                                    <option value="TABLE_RESERVATION">TABLE RESERVATION</option>
                                    <option value="ROOM_RESERVATION">ROOM RESERVATION</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('serviceType')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.serviceType[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="business_category">Business Category</label>
                                <select id="business_category" class="form-control" v-model="business.businessCategory">
                                    <option value="">Choose Business Category...</option>
                                    <option value="HOTEL">HOTEL</option>
                                    <option value="RESTAURANT">RESTAURANT</option>
                                    <option value="SERVICE_APARTMENT">SERVICE APARTMENT</option>
                                    <option value="MOVIE_THEATER">MOVIE THEATER</option>
                                    <option value="SPA">SPA</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('businessCategory')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.businessCategory[0] }}
                                </div>
                            </div>
                            </div>

                            <div class="form-row mt-1">
                                <div id="tooltips" class="col-lg-12  col-md-12">
                                    <div class="widget-content ">
                                        <label for="editor-container">Short Description</label>
                                        <QuillEditor theme="snow" v-model:content="business.shortDescription" contentType="html" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-1">
                                <div id="tooltips" class="col-lg-12 col-md-12">
                                    <div class="widget-content ">
                                        <label for="editor-container">Long Description</label>
                                        <QuillEditor theme="snow" v-model:content="business.longDescription" contentType="html" />
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
                                <label for="location_point">Location- Location link from Google Map.</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('location_point') ? 'is-invalid' : ''" id="location_point" placeholder="google Location Link" v-model="business.locationPoint" >
                                <div
                                        v-if="validation_error.hasOwnProperty('locationPoint')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.locationPoint[0] }}
                                    </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="BusinessOwner">Business Owner</label>
                                <input type="text" class="form-control form-control-sm" id="BusinessOwner" placeholder="Business Owner Info: name, phone, address" v-model="business.businessOwner" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="business_manager">Business Manager</label>
                                <input type="text" class="form-control form-control-sm" id="business_manager" placeholder="Business Manager: name, phone, address" v-model="business.businessManager" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tin">TIN</label>
                                <input type="text" class="form-control form-control-sm" id="tin" placeholder="TIN Number" v-model="business.tin" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bin">BIN</label>
                                <input type="text" class="form-control form-control-sm" id="bin" placeholder="BIN Number" v-model="business.bin" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="number_of_employee">Number Of Employees</label>
                                <input type="number" min="0" class="form-control form-control-sm" id="number_of_employee" placeholder="Total Employees" v-model="business.numberOfEmployee" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="trade_licence">Trade Licence Number</label>
                                <input type="text" class="form-control form-control-sm" id="trade_licence" placeholder="Enter Trade Licence Number" v-model="business.tradeLicence" />
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

        <button class="btn btn-success mt-1 btn-lg" type="submit">
            <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>Save</button>
    </form>
</template>
<style scoped>

.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    position: relative;
    width: 100%;
    padding-right: 0px;
    padding-left: 15px;
}

.image-close {
  position: absolute;
  font-size: 1.9rem;
  z-index: 2;
}

.image-close:hover:before {
  opacity: 1;
  transition: all 200ms ease;
}
</style>
