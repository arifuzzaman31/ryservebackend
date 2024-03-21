<script>
import axios from "axios";
import Mixin from "../../mixer";
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
export default {
    mixins: [Mixin],
    components: {QuillEditor},
    data() {
        return {
            updateAsset : {
                id: '',
                businessId: '',
                assetType: '',
                propertyName: '',
                country: '',
                city: '',
                area: '',
                locationPoint: '',
                geoTag: '',
                noOfRoom: '',
                logo: '',
                about: '',
                status: 'true'
            },
            assetes: [],
            businesses: [],
            url: baseUrl,
            isSubmiting: false,
            validation_error: {},
        };
    },
    methods: {
        async getAsset() {
            try {
                const tok = localStorage.getItem("authuser");
                const token = JSON.parse(tok);
                await axios
                    .get(`${apiUrl}backendapi/asset`, {
                        headers: {
                            Authorization: `Bearer ${token.token}`,
                        },
                    })
                    .then((response) => {
                        this.assetes = response.data;
                        // console.log(response.data)
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } catch (e) {
                console.log(e);
            }
        },
        async setupData(assetData){
                this.updateAsset.id = assetData.id
                this.updateAsset.businessId = assetData.businessId
                this.updateAsset.assetType = assetData.assetType
                this.updateAsset.propertyName = assetData.propertyName
                this.updateAsset.country = assetData.country
                this.updateAsset.city = assetData.city
                this.updateAsset.area = assetData.area
                this.updateAsset.locationPoint = assetData.locationPoint
                this.updateAsset.geoTag = assetData.geoTag
                this.updateAsset.noOfRoom = assetData.noOfRoom
                this.updateAsset.logo = assetData.logo
                this.updateAsset.about = assetData.about
                this.updateAsset.status = assetData.status.toString()
        },
        async editAsset(asset){
            this.getBusiness();
            await this.setupData(asset)
            $("#updateAssetModal").modal('show');
        },
        async updateAssetData(){
            try{
                this.isSubmiting = true
                    const token = await this.getUserToken()
                    await axios.put(`${apiUrl}backendapi/asset?id=${this.updateAsset.id}`,this.updateAsset, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    }).then(
                        response => {
                            if (response.status == 200) {
                                this.successMessage({ status: 'success', message: 'Asset Updated Successful' })
                                $("#updateAssetModal").modal('hide');
                                this.getAsset()
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
        async getBusiness(){
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
        clearForm(){
            this.updateAsset = {
                id: '',
                businessId: '',
                assetType: '',
                propertyName: '',
                country: '',
                city: '',
                area: '',
                locationPoint: '',
                geoTag: '',
                noOfRoom: '',
                logo: '',
                about: '',
                status: 'true'
            },
            this.validation_error = {}
        },
    },
    computed: {
        showPermission() {
            return this.getUserPermission();
        }
    },
    mounted() {
        this.getAsset();
    },
};
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div
                            class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between"
                        >
                            <h4>Branch</h4>
                            <a v-if="showPermission.includes('branch-create')"
                                href="create/asset"
                                class="btn btn-primary mb-2 mr-3"
                            >
                                Create Branch
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Branch Name</th>
                                    <th>Asset Type</th>
                                    <th>City</th>
                                    <th>Area</th>
                                    <th>Total Booking</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" v-if="showPermission.includes('branch-edit')" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template
                                    v-for="(asset, index) in assetes"
                                    :key="asset.id"
                                >
                                    <tr>
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ asset.propertyName }}</td>
                                        <td>{{ strippedContent(asset.assetType) }}</td>
                                        <td>{{ asset.city }}</td>
                                        <td>{{ asset.area }}</td>
                                        <td>{{ asset.bookingCount }}</td>
                                        <td class="text-center">
                                            {{ asset.status == true ? 'Active' : 'Deactive' }}
                                        </td>
                                        <td v-if="showPermission.includes('branch-edit')" >
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" v-if="showPermission.includes('branch-edit')" @click="editAsset(asset)" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                        </a></li> -->
                                    <!-- <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li> -->
                                    <!-- <li><a href="asset" title="Asset">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a></li> -->
                                </ul>
                            </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="updateAssetModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog" v-if="updateAsset.id">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{ updateAsset.propertyName }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" @submit.prevent="updateAssetData()" id="add-asset-form">
                            <div class="row">
                                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                                    <div class="statbox widget box ">
                                        <div class="widget-content ">
                                            <div class="form-row">
                                                <div class="col-md-4">
                                                    <label for="propertyName">Branch Name</label>
                                                    <input type="text" class="form-control" :class="validation_error.hasOwnProperty('propertyName') ? 'is-invalid' : ''" id="propertyName" placeholder="Branch name" v-model="updateAsset.propertyName" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('propertyName')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.propertyName[0] }}
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="business_type">Select Business</label>
                                                    <select id="business_type" class="form-control" v-model="updateAsset.businessId">
                                                        <option value="">Choose Business...</option>
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
                                                    <label for="assetType">Asset Type</label>
                                                    <select id="assetType" class="form-control" v-model="updateAsset.assetType">
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
                                                            <QuillEditor theme="snow" v-model:content="updateAsset.about" contentType="html" />
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
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('country') ? 'is-invalid' : ''" id="country" placeholder="Country" v-model="updateAsset.country">
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('country')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.country[0] }}
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-4 mb-3">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('city') ? 'is-invalid' : ''" id="ity" placeholder="City" v-model="updateAsset.city" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('city')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.city[0] }}
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-4 mb-3">
                                                    <label for="area">Area</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('area') ? 'is-invalid' : ''" id="area" placeholder="Area Name" v-model="updateAsset.area" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('area')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.area[0] }}
                                                        </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="locationPoint">Location- Location link from Google Map</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('locationPoint') ? 'is-invalid' : ''" id="locationPoint" placeholder="google Location Link" v-model="updateAsset.locationPoint" >
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('locationPoint')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.locationPoint[0] }}
                                                        </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="logo">Logo Link</label>
                                                    <input type="text" class="form-control form-control-sm" id="logo" placeholder="Logo" v-model="updateAsset.logo" />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="geoTag">Geo Tag</label>
                                                    <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('geoTag') ? 'is-invalid' : ''" id="geoTag" placeholder="google geo tag" v-model="updateAsset.geoTag" >
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('geoTag')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.geoTag[0] }}
                                                        </div>
                                                </div>


                                                <div class="col-md-4 mb-3">
                                                    <label for="no_of_room">Number Of Rooms</label>
                                                    <input type="number" min="1" class="form-control form-control-sm" id="no_of_room" placeholder="Total Rooms" v-model="updateAsset.noOfRoom" />
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="status">Status</label>
                                                    <select id="status" class="form-control" v-model="updateAsset.status">
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
                                <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>
                                Update</button>
                        </form>
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
