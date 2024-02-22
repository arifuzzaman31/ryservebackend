<script>
import axios from 'axios';
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    components: {
    },

    data(){
        return {
            subasset : {
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                amenities: [
                    {
                        "name": "Ac Room",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Reception",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Swimming Pool",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 120,
                        "status": true
                    },
                    {
                        "name": "Laundry Service",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 150,
                        "status": true
                    },
                    {
                        "name": "Airport Shuttle",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 180,
                        "status": true
                    },
                    {
                        "name": "Gym",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Parking",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Kitchen",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Smoking",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Pets",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 190,
                        "status": true
                    },
                    {
                        "name": "CCTV",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": true
                    },
                    {
                        "name": "Wifi",
                        "icon": "https://cdn4.iconfinder.com/data/icons/vecico-connectivity/288/wifi_Symbol-512.png",
                        "price": 0,
                        "status": false
                    }
                ],
                address: '',
                status: true
            },
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
                    console.log(result.data)
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


        clearForm() {
            this.subasset = {
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                amenities: {},
                address: '',
                status: true
            }
            this.validation_error = {}

        },
    },
    mounted(){
        this.getUserBusiness()
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

        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="floor">Floor (Level)</label>
                                <input type="number" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('floor') ? 'is-invalid' : ''" id="floor" placeholder="Floor" v-model="subasset.floor">
                                <div
                                        v-if="validation_error.hasOwnProperty('country')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.country[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="sqft">Size (sqft)</label>
                                <input type="number" step="any" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('sqft') ? 'is-invalid' : ''" id="sqft" placeholder="Size" v-model="subasset.sqft" >
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
