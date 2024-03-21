<script>
import axios from 'axios';
import Mixin from '../../mixer'
import Multiselect from '@vueform/multiselect'
export default {
    mixins:[Mixin],
    components: {
        Multiselect
    },

    data(){
        return {
            subasset : {
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                address: '',
                // amini:[],
                amenities: [{name:'',icon:'',price:0,status:true}],
                status: 'true'
            },
            amenities: [],
            businesses: [],
            assets: [],
            validation_error: {},
        }
    },
    methods: {
        createSubAsset(){
            const tok = localStorage.getItem('authuser')
            const token = JSON.parse(tok)
            axios.post(`${apiUrl}backendapi/sub-asset`, this.subasset, {
                headers: {
                    'Authorization': `Bearer ${token.token}`
                }
            })
                .then((result) => {
                    // console.log(result.data)
                    if(result.status == 201){
                        this.successMessage({status:'success',message:'New Sub Asset Created Successful'})
                        window.location.href = baseUrl+'sub-asset'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        getUserBusiness(){
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
        getAssetByBusiness(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                this.assets = []
                 axios.get(`${apiUrl}backendapi/asset?businessId=${this.subasset.businessId}`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then(response => {
                    this.assets = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        getAmenities(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                this.assets = []
                 axios.get(`${apiUrl}backendapi/amenities?status=yes`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then(response => {
                    this.amenities = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        removeCatChild(index) {
            if(index == 0) return ;
            this.subasset.amenities.splice(index, 1);
        },
        addMore(){
            this.subasset.amenities.push({name:'',icon:'',price:0,status:true})
        },

        async setItem(data){

            const index = this.amenities.findIndex((selectedOption) => selectedOption.name == data);
            if (index !== -1) {
                // this.selectedAmenities.splice(index, 1);
                const ami = this.amenities[index]
                const ind = this.subasset.amenities.findIndex((selectedOption) => selectedOption.name == data);
                if (ind !== -1) {
                    this.subasset.amenities[ind] = { ...this.subasset.amenities[ind], icon: ami.icon };
                } else {
                    this.subasset.amenities.push({ name: data, icon: ami.icon });
                }
            }
            this.subasset.amenities = await this.subasset.amenities.filter(item => item.icon !== '');
        },

        clearForm() {
            this.subasset = {
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                amenities: [{name:'',icon:'',price:0,status:true}],
                address: '',
                status: 'true'
            }
            this.validation_error = {}

        },
    },
    mounted(){
        this.getUserBusiness()
        this.getAmenities()
    }
}
</script>
<template>
     <div class="widget-header">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                <h4>Create New Sub Asset</h4>
            </div>
        </div>

    <form class="needs-validation" method="post" @submit.prevent="createSubAsset()" id="add-SubAsset-form">
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="business_type">Select Business</label>
                                <select id="business_type" class="form-control" v-model="subasset.businessId" @change="getAssetByBusiness()">
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
                                <label for="business_type">Select Asset</label>
                                <select id="business_type" class="form-control" v-model="subasset.assetId">
                                    <option value="">Choose Asset...</option>
                                    <option v-for="(asset,ind) in assets" :key="ind" :value="asset.id">{{asset.propertyName}}</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('assetId')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.assetId[0] }}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="service_type">Address</label>
                                <input type="text" class="form-control form-control-sm" id="address" placeholder="Address" v-model="subasset.address" >
                                <div
                                    v-if="validation_error.hasOwnProperty('address')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.address[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <h5>Amenities</h5>
                <div class="widget-content ">
                    <div class="row text-center">
                        <div class="col-8 text-success">
                            <b>Name</b>
                        </div>
                        <!-- <div class="col-3  text-success">
                           <b>Icon</b>
                        </div> -->
                        <div class="col-2  text-success">
                            <b>Price</b>
                        </div>
                        <!-- <div class="text-success col-2">
                            <b>Status</b>
                        </div> -->
                        <div class="col-1  text-danger">
                            <b>Remove</b>
                        </div>
                    </div>
                    <div class="row" v-for="(ameni,index) in subasset.amenities" :key="index">
                        <div class="form-group col-md-8">
                            <Multiselect v-model="ameni.name" placeholder="Select Amenities" track-by="name"
                            label="name" :close-on-select="true" :search="true" :options="amenities"
                            :searchable="true" @select="setItem">
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                                <template v-slot:option="{ option, select, selected }">
                                    <div @click="select(option)" class="custom-option">
                                    <img :src="option.icon" alt="Amenity Icon" class="option-image" />
                                    <span>{{ option.name }}</span>
                                    <span v-if="selected" class="multiselect-option__select">
                                        &#x2713;
                                    </span>
                                    </div>
                                </template>
                            </Multiselect>
                        </div>
                        <div class="form-group col-2">
                            <input type="number" min="0" class="form-control form-control-sm" id="price" v-model="ameni.price" placeholder="Price" required>
                        </div>
                        <div class="form-group form-control-sm col-md-1 text-center">
                            <a
                              href="javascript:void(0)"
                              @click.prevent="removeCatChild(index)"
                              class="mt-5"
                              ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                        </div>
                    </div>
                </div>
                <a
                        href="javascript:void(0)"
                        @click.prevent="addMore()"
                        class="btn btn-warning"
                    >Add More
                    </a>
        </div>

        <div class="row mt-4">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="floor">Floor (Level)</label>
                                <input type="number" min="0" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('floor') ? 'is-invalid' : ''" id="floor" placeholder="Floor" v-model="subasset.floor">
                                <div
                                        v-if="validation_error.hasOwnProperty('country')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.country[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="sqft">Size (sqft)</label>
                                <input type="number"  min="0" step="any" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('sqft') ? 'is-invalid' : ''" id="sqft" placeholder="Size" v-model="subasset.sqft" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('sqft')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.sqft[0] }}
                                    </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" v-model="subasset.status">
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
.option-image {
  width: 20px; /* Adjust the size as needed */
  height: 20px;
  margin-right: 5px;
}

.multiselect-option__select {
  color: green;
  font-size: 18px;
}
</style>
