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
            assets : {
                businessId: '',
                assetType: '',
                propertyName: '',
                country: '',
                city: '',
                area:'',
                locationPoint: '',
                geoTag: '',
                noOfRoom: '',
                logo: '',
                about: '',
                status: 'true'
            },
            isSubmiting:false,
            businesses: [],
            validation_error: {},
        }
    },
    methods: {
        async createAsset(){
            this.isSubmiting = true
            const token = await this.getUserToken()
            await axios.post(`${apiUrl}backendapi/asset`, this.assets, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((result) => {
                    if(result.status == 201){
                        this.isSubmiting = false
                        this.successMessage({status:'success',message:'New Asset Created Successful'})
                        window.location.href = baseUrl+'asset'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
                this.isSubmiting = false
        },
        async getAssetByassetId(){
            try{
                const token = await this.getUserToken()
                 axios.get(`${apiUrl}backendapi/business`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.businesses = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },


        clearForm() {
            this.assets = {
                businessId: '',
                assetType: '',
                propertyName: '',
                country: '',
                city: '',
                area:'',
                locationPoint: '',
                geoTag: '',
                noOfRoom: '',
                logo: '',
                about: '',
                status: 'true'
            }
            this.validation_error = {}

        },
    },
    mounted(){
        this.getAssetByassetId()
    }
}
</script>
<template>
     <div class="widget-header">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                <h4>Create New Branch</h4>
            </div>
        </div>

    <form class="needs-validation" method="post" @submit.prevent="createAsset()" id="add-asset-form">
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="property_name">Branch Name</label>
                                <input type="text" class="form-control" :class="validation_error.hasOwnProperty('property_name') ? 'is-invalid' : ''" id="property_name" placeholder="Branch name" v-model="assets.propertyName" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('propertyName')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.propertyName[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="business_type">Select Business</label>
                                <select id="business_type" class="form-control" v-model="assets.businessId">
                                    <option value="">Choose a Business...</option>
                                    <option v-for="(business,ind) in businesses" :key="ind" :value="business.id">{{business.businessName}}</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('businessId')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.businessId[0] }}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="Asset_type">Asset Type</label>
                                <select id="Asset_type" class="form-control" v-model="assets.assetType">
                                    <option value="">Choose Asset Type...</option>
                                    <option value="APARTMENT_BUILDING">APARTMENT BUILDING</option>
                                    <option value="SHARED_BUILDING">SHARED BUILDING</option>
                                    <option value="SHARED_FLOOR">SHARED FLOOR</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('assetType')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.assetType[0] }}
                                </div>
                            </div>

                            </div>
                            <div class="form-row mt-1">
                                <div id="tooltips" class="col-lg-12 col-md-12">
                                    <div class="widget-content ">
                                        <label for="editor-container">About</label>
                                        <QuillEditor theme="snow" v-model:content="assets.about" contentType="html" placeholder="Write Description/House Rules" />
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
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('country') ? 'is-invalid' : ''" id="country" placeholder="Country" v-model="assets.country">
                                <div
                                        v-if="validation_error.hasOwnProperty('country')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.country[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('city') ? 'is-invalid' : ''" id="ity" placeholder="City" v-model="assets.city" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('city')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.city[0] }}
                                    </div>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="area">Area</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('area') ? 'is-invalid' : ''" id="area" placeholder="Area" v-model="assets.area" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('area')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.area[0] }}
                                    </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location_point">Location link from Google Map</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('location_point') ? 'is-invalid' : ''" id="location_point" placeholder="google Location Link" v-model="assets.locationPoint" >
                                <div
                                        v-if="validation_error.hasOwnProperty('locationPoint')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.locationPoint[0] }}
                                    </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="logo">Logo Link</label>
                                <input type="text" class="form-control form-control-sm" id="logo" placeholder="Logo" v-model="assets.logo" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="geo_tag">Geo Tag</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('geoTag') ? 'is-invalid' : ''" id="geo_tag" placeholder="Latititude,Longitude" v-model="assets.geoTag" >
                                <div
                                        v-if="validation_error.hasOwnProperty('geoTag')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.geoTag[0] }}
                                    </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <label for="no_of_room">Number Of Rooms</label>
                                <input type="number" min="1" class="form-control form-control-sm" id="no_of_room" placeholder="Total Rooms" v-model="assets.noOfRoom" />
                            </div>

                            <div class="form-group col-md-4">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" v-model="assets.status">
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
</div>
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
