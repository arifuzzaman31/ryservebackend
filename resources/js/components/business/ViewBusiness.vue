<script>
import axios from 'axios';
import Mixin from '../../mixer';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default {
    mixins:[Mixin],
    components:{
        QuillEditor
    },

    data(){
        return {
            updateData : {
                id: '',
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
            businesses: [],
            url: baseUrl,
            isSubmiting: false,
            isLoading: false,
            validation_error: {},
        }
    },
    methods:{
        async getBusiness(){
            try{
                this.isLoading = true
                const token = await this.getUserToken()
                axios.get(`${apiUrl}backendapi/business`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.businesses = response.data
                    this.isLoading = false
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
            this.isLoading = false
        },

        deleteCampaign(id){
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
                  axios.delete(apiUrl+`business?id=${id}`).then(
                      response => {
                          this.successMessage(response.data)
                        // this.getCampaign()
                        //   console.log(response.data)
                      }
                  ). catch(error => {
                    console.log(error)
                  })
              }
          })

        },

        clearForm(){
            this.updateData = {
                id: '',
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
            this.validation_error = {}
        },
        async setupData(businessData){
                this.updateData.id = businessData.id
                this.updateData.businessName = businessData.businessName
                this.updateData.shortDescription = businessData.shortDescription
                this.updateData.longDescription = businessData.longDescription
                this.updateData.businessType = businessData.businessType
                this.updateData.serviceType = businessData.serviceType
                this.updateData.businessCategory = businessData.businessCategory
                this.updateData.country = businessData.country
                this.updateData.city = businessData.city
                this.updateData.locationPoint = businessData.locationPoint
                this.updateData.businessOwner = businessData.businessOwner
                this.updateData.businessManager = businessData.businessManager
                this.updateData.numberOfEmployee = businessData.numberOfEmployee
                this.updateData.tradeLicence = businessData.tradeLicence
                this.updateData.tin = businessData.tin
                this.updateData.bin = businessData.bin
                this.updateData.status = businessData.status.toString()

        },
        async editBusiness(business){
            await this.setupData(business)
            $("#updateBusinessModal").modal('show');
        },

        async updateBusiness(){
         try{
            this.isSubmiting = true
                const token = await this.getUserToken()
                await axios.put(`${apiUrl}backendapi/business?id=${this.updateData.id}`,this.updateData, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        if (response.status == 200) {
                            this.successMessage({ status: 'success', message: 'Business Updated Successful' })
                            $("#updateBusinessModal").modal('hide');
                            this.getBusiness()
                        }
                        this.clearForm()
                    }
                ).catch(e => {
                    if (e.response.status == 422) {
                        this.validation_error = e.response.data.errors;
                        this.validationError();
                    }else{
                        this.validationError();
                    }
                })
                this.isSubmiting = false
          }catch(e){
             console.log(e.response)
          }
      },

    },
    computed: {
    },
    mounted(){
        this.getBusiness()
    }
}
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Business</h4>
                <a :href="url+'create/business'"
                    class="btn btn-primary mb-2 mr-3"
                >
                    Create Business
        </a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area text-center" v-if="isLoading">
                    <div class="spinner-border text-success align-self-center loader-xl"></div>
                </div>
                <div class="widget-content widget-content-area" v-else>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Business Name</th>
                            <th>Business Type</th>
                            <th>Service Type</th>
                            <th>Business Category</th>
                            <th>Area</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="businesses && businesses.length > 0">
                        <template v-for="(business,index) in businesses" :key="business.id">
                            <tr>
                                <td>{{ index+1 }}</td>
                                <td>{{ business.businessName }}</td>
                                <td>{{ business.businessType }}</td>
                                <td>{{ strippedContent(business.serviceType) }}</td>
                                <td>{{ strippedContent(business.businessCategory) }}</td>
                                <td>{{ business.city }}</td>
                                <td class="text-center">
                                        {{business.status == true ? 'Active' : 'Inactive'}}
                                </td>
                                <td>
                                    <ul class="table-controls d-flex justify-content-around">
                                        <li><a href="javascript:void(0);" @click="editBusiness(business)" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                        <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                            </a></li> -->
                                        <!-- <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li> -->
                                        <!-- <li><a href="javascript:void(0);" title="Asset">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                        </a></li> -->
                                    </ul>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody v-else>
                            <tr class="text-center text-bold">
                                <td colspan="8">No Data Found</td>
                            </tr>
                        </tbody>
                </table>
                    </div>
                </div>
            </div>
            <div id="updateBusinessModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog" v-if="updateData.id">
                <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{ updateData.businessName }}</h5>
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
                                                    <input type="text" class="form-control" :class="validation_error.hasOwnProperty('businessName') ? 'is-invalid' : ''" id="Business-name" placeholder="Business name" v-model="updateData.businessName" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('businessName')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.businessName[0] }}
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="business_type">Business Type</label>
                                                    <select id="business_type" class="form-control" v-model="updateData.businessType">
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
                                                    <select id="service_type" class="form-control" v-model="updateData.serviceType">
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
                                                    <select id="business_category" class="form-control" v-model="updateData.businessCategory">
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
                                                            <QuillEditor theme="snow" v-model:content="updateData.shortDescription" contentType="html" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row mt-1">
                                                    <div id="tooltips" class="col-lg-12 col-md-12">
                                                        <div class="widget-content ">
                                                            <label for="editor-container">Long Description</label>
                                                            <QuillEditor theme="snow" v-model:content="updateData.longDescription" contentType="html" />
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
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('country') ? 'is-invalid' : ''" id="country" placeholder="Country" v-model="updateData.country">
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('country')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.country[0] }}
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-4 mb-3">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('city') ? 'is-invalid' : ''" id="ity" placeholder="City" v-model="updateData.city" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('city')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.city[0] }}
                                                        </div>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="location_point">Location- Location link from Google Map</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('location_point') ? 'is-invalid' : ''" id="location_point" placeholder="Location link from Google Map" v-model="updateData.locationPoint" >
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('locationPoint')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.locationPoint[0] }}
                                                        </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="BusinessOwner">Business Owner</label>
                                                    <input type="text" class="form-control form-control-sm" id="BusinessOwner" placeholder="Business Owner Info: name, phone, address" v-model="updateData.businessOwner" />
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label for="business_manager">Business Manager</label>
                                                    <input type="text" class="form-control form-control-sm" id="business_manager" placeholder="Business Manager: name, phone, address" v-model="updateData.businessManager" />
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="tin">TIN</label>
                                                    <input type="text" class="form-control form-control-sm" id="tin" placeholder="TIN Number" v-model="updateData.tin" />
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="bin">BIN</label>
                                                    <input type="text" class="form-control form-control-sm" id="bin" placeholder="BIN Number" v-model="updateData.bin" />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="number_of_employee">Number Of Employees</label>
                                                    <input type="number" class="form-control form-control-sm" id="number_of_employee" placeholder="Total Employees" v-model="updateData.numberOfEmployee" />
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label for="trade_licence">Trade Licence</label>
                                                    <input type="text" class="form-control form-control-sm" id="trade_licence" placeholder="Enter Trade Licence Number" v-model="updateData.tradeLicence" />
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="status">Status</label>
                                                    <select id="status" class="form-control" v-model="updateData.status">
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

                            <button class="btn btn-info mt-1 btn-lg" type="submit">
                                <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>
                                Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

</template>


<style scoped>
.modal-xl{
    max-width: 86% !important;
}
</style>
