<script>
import axios from 'axios';
import Mixin from '../../mixer'
import Multiselect from '@vueform/multiselect'
export default {
    mixins:[Mixin],
    components:{
        Multiselect
    },

    data(){
        return {
            updatesubasset : {
                id: '',
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                address: '',
                amenities: [{name:'',icon:'',price:0,status:true}],
                status: 'true'
            },
            amenities: [],
            businesses: [],
            assets: [],
            subassetes: [],
            isSubmiting: false,
            url: baseUrl,
            validation_error: {},
        }
    },
    methods:{
        async getSubAsset(){
            try{
                const token = await this.getUserToken()
                await axios.get(`${apiUrl}backendapi/sub-asset`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.subassetes = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        async getUserBusiness(){
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
        async getAssetByBusiness(){
            try{
                const token = await this.getUserToken()
                this.assets = []
                 axios.get(`${apiUrl}backendapi/asset?businessId=${this.updatesubasset.businessId}`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
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
        async getAmenities(){
            try{
                const token = await this.getUserToken()
                this.assets = []
                 axios.get(`${apiUrl}backendapi/amenities?status=yes`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
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
        async editSubAsset(subasset){
            this.getUserBusiness();
            this.clearForm()
            await this.setupData(subasset)
            // console.log(this.updatesubasset)
            $("#updateSubAssetModal").modal('show');
        },
        async setupData(subassetData){
            this.updatesubasset.id = subassetData.id
            this.updatesubasset.assetId = subassetData.assetId
            this.updatesubasset.sqft = subassetData.sqft
            this.updatesubasset.floor = subassetData.floor
            this.updatesubasset.address = subassetData.address
            this.updatesubasset.amenities = subassetData.amenities
            this.updatesubasset.status = subassetData.status.toString()
        },
        async updateSubAssetData(){
            const token = await this.getUserToken()
            delete this.updatesubasset['businessId'];
            axios.put(`${apiUrl}backendapi/sub-asset?id=${this.updatesubasset.id}`, this.updatesubasset, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((result) => {
                    if(result.status == 200){
                        $("#updateSubAssetModal").modal('hide');
                        this.successMessage({status:'success',message:'Sub Asset Updated Successful'})
                        this.getSubAsset()
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        removeCatChild(index) {
            if(index == 0) return ;
            this.updatesubasset.amenities.splice(index, 1);
        },
        addMore(){
            this.updatesubasset.amenities.push({name:'',icon:'',price:0,status:true})
        },

        async setItem(data){

            const index = this.amenities.findIndex((selectedOption) => selectedOption.name == data);
            if (index !== -1) {
                const ami = this.amenities[index]
                const ind = this.updatesubasset.amenities.findIndex((selectedOption) => selectedOption.name == data);
                if (ind !== -1) {
                    this.updatesubasset.amenities[ind] = { ...this.updatesubasset.amenities[ind], icon: ami.icon };
                }
            }
            const uniqueNamesSet = new Set();
            const resultArray = this.updatesubasset.amenities.filter(item => {
            if (!uniqueNamesSet.has(item.name)) {
                uniqueNamesSet.add(item.name);
                return true;
            }
                return false;
            });
            this.updatesubasset.amenities = resultArray
        },
        async clearForm(){
            this.updatesubasset = {
                id: '',
                businessId: '',
                assetId: '',
                sqft: '',
                floor: '',
                address: '',
                amenities: [{name:'',icon:'',price:0,status:true}],
                status: 'true'
            }
        }

    },
    computed: {
    },
    mounted(){
        this.getSubAsset()
        // this.getUserBusiness()
        this.getAmenities()
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
                            <h4>Sub Asset</h4>
                <a href="create/subasset"
                    class="btn btn-primary mb-2 mr-3"
                >
                    Create SubAsset
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
                            <th>Sub Asset ID</th>
                            <th>Floor</th>
                            <th>SQFT</th>
                            <th>Address</th>
                            <th>Aminities</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(subasset,index) in subassetes" :key="subasset.id">
                            <tr>
                                <td>{{ index+1 }}</td>
                                <td>{{ subasset.id }}</td>
                                <td>{{ subasset.floor }}</td>
                                <td>{{ subasset.sqft }}</td>
                                <td>{{ subasset.address }}</td>
                                <td>
                                    <span v-for="(amini,ind) in subasset.amenities" :key="ind">
                                        {{ amini.name }},
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span>{{ subasset.status == 1 ? 'Active' : 'Deactive' }}</span>
                                </td>
                                <td>
                                    <ul class="table-controls d-flex justify-content-around">
                                        <li><a href="javascript:void(0);" @click="editSubAsset(subasset)" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                        <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                            </a></li> -->
                                        <!-- <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li> -->

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
        <div id="updateSubAssetModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog" v-if="updatesubasset.id">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" @submit.prevent="updateSubAssetData()" id="add-SubAsset-form">
                            <div class="row">
                                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                                    <div class="statbox widget box ">
                                        <div class="widget-content ">
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="business_type">Select Business</label>
                                                    <select id="business_type" class="form-control" v-model="updatesubasset.businessId" @change="getAssetByBusiness()">
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
                                                    <select id="business_type" class="form-control" v-model="updatesubasset.assetId">
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
                                                    <input type="text" class="form-control form-control-sm" id="address" placeholder="Address" v-model="updatesubasset.address" >
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
                                        <div class="row" v-for="(ameni,index) in updatesubasset.amenities" :key="index">
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
                                                <input type="number"  class="form-control form-control-sm" id="price" v-model="ameni.price" placeholder="Price" required>
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
                                                    <input type="number" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('floor') ? 'is-invalid' : ''" id="floor" placeholder="Floor" v-model="updatesubasset.floor">
                                                    <div
                                                            v-if="validation_error.hasOwnProperty('country')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.country[0] }}
                                                        </div>
                                                </div>

                                                <div class="form-group col-md-4 mb-3">
                                                    <label for="sqft">Size (sqft)</label>
                                                    <input type="number" step="any" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('sqft') ? 'is-invalid' : ''" id="sqft" placeholder="Size" v-model="updatesubasset.sqft" >
                                                        <div
                                                            v-if="validation_error.hasOwnProperty('sqft')"
                                                            class="invalid-feedback"
                                                        >
                                                            {{ validation_error.sqft[0] }}
                                                        </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="status">Status</label>
                                                    <select id="status" class="form-control" v-model="updatesubasset.status">
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

                            <button class="btn btn-success mt-1 btn-lg" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
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
