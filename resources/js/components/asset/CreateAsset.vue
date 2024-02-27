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
                asset_type: '',
                property_name: '',
                country: '',
                city: '',
                location_point: '',
                geo_tag: '',
                no_of_room: '',
                logo: '',
                about: '',
                status: 'true'
            },
            businesses: [],
            validation_error: {},
        }
    },
    methods: {
        createAsset(){
            const tok = localStorage.getItem('authuser')
            const token = JSON.parse(tok)
            axios.post(`${apiUrl}backendapi/asset`, this.assets, {
                headers: {
                    'Authorization': `Bearer ${token.token}`
                }
            })
                .then((result) => {
                    if(result.status == 201){
                        this.successMessage({status:'success',message:'New Asset Created Successful'})
                        window.location.href = baseUrl+'asset'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        getAssetByassetId(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                 axios.get(`${apiUrl}backendapi/business`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
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
            this.business = {
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
                <h4>Create New Asset</h4>
            </div>
        </div>

    <form class="needs-validation" method="post" @submit.prevent="createAsset()" id="add-product-form">
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="property_name">Property Name</label>
                                <input type="text" class="form-control" :class="validation_error.hasOwnProperty('property_name') ? 'is-invalid' : ''" id="property_name" placeholder="Property name" v-model="assets.property_name" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('property_name')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.property_name[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="business_type">Select Business</label>
                                <select id="business_type" class="form-control" v-model="assets.businessId">
                                    <option value="">Choose Business Type...</option>
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
                                <label for="service_type">Asset Type</label>
                                <select id="service_type" class="form-control" v-model="assets.asset_type">
                                    <option value="">Choose Asset Type...</option>
                                    <option value="APARTMENT_BUILDING">APARTMENT_BUILDING</option>
                                    <option value="SHARED_BUILDING">SHARED_BUILDING</option>
                                    <option value="SHARED_FLOOR">SHARED_FLOOR</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('asset_type')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.asset_type[0] }}
                                </div>
                            </div>

                            </div>
                            <div class="form-row mt-1">
                                <div id="tooltips" class="col-lg-12 col-md-12">
                                    <div class="widget-content ">
                                        <label for="editor-container">About</label>
                                        <QuillEditor theme="snow" v-model:content="assets.about" contentType="html" />
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

                            <div class="col-md-4 mb-3">
                                <label for="location_point">Location Point</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('location_point') ? 'is-invalid' : ''" id="location_point" placeholder="google Location Link" v-model="assets.location_point" >
                                <div
                                        v-if="validation_error.hasOwnProperty('location_point')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.location_point[0] }}
                                    </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="logo">Logo Link</label>
                                <input type="text" class="form-control form-control-sm" id="logo" placeholder="Logo" v-model="assets.logo" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="geo_tag">Geo Tag</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('geo_tag') ? 'is-invalid' : ''" id="geo_tag" placeholder="google geo tag" v-model="assets.geo_tag" >
                                <div
                                        v-if="validation_error.hasOwnProperty('geo_tag')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.geo_tag[0] }}
                                    </div>
                            </div>


                            <div class="col-md-4 mb-3">
                                <label for="no_of_room">Number Of Room</label>
                                <input type="number" class="form-control form-control-sm" id="no_of_room" placeholder="Total Room" v-model="assets.no_of_room" />
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

        <button class="btn btn-success mt-1 btn-lg" type="submit">Save</button>
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
