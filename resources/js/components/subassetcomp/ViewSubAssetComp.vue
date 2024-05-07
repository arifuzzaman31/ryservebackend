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
            table: {
                subAssetCompId: '',
                capacity: '',
                type: '',
                position: '',
                size: '',
                splitable: 'true',
                ryservable: 'true',
                status: 'true'
            },
            updateComponent:{
                id: '',
                assetId: '',
                subAssetId: '',
                pricingId: '',
                pricingId: '',
                listingName: '',
                type: '',
                isEvent: false,
                event: {
                    expireDate: '',
                    eventName:'',
                    eventIcon:'',
                    slot:''
                },
                cuisines: [],
                slot: [],
                offday: [{ dayname: '', from: '', to: '' }],
                pricing: [{itemName: 'Demo', image: '',qty: 1, size: '120 cm', weight: '250 gm',
                price:0,description:'Here is demo description.'}],
                description: '',
                terms: '',
                reservationCategory: '',
                image: [{link:'',precedence:0}],
                status: "true",
                table: [{ id:'',capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" }]
            },
            subassetescomp: [],
            assets: [],
            subassets: [],
            cuisineList : [{name:"American",value:"American"},{name:"Chinese",value:"Chinese"},{name:"Italian",value:"Italian"},
            {name:"Spanish",value:"Spanish"},{name:"French",value:"French"},{name:"Indian",value:"Indian"},{name:"Bengali",value:"Bengali"}],
            slotdev: [{ dayname: '', sl: [] }],
            makeSlot: [{ slottime: '6 AM', value: '6 AM', status: true },{ slottime: '7 AM', value: '7 AM', status: true }, { slottime: '8 AM', value: '8 AM', status: true }, { slottime: '9 AM', value: '9 AM', status: true },
                { slottime: '10 AM', value: '10 AM', status: true }, { slottime: '11 AM', value: '11 AM', status: true }, { slottime: '12 PM', value: '12 PM', status: true },
                { slottime: '1 PM', value: '1 PM', status: true }, { slottime: '2 PM', value: '2 PM', status: true }, { slottime: '3 PM', value: '3 PM', status: true },
                { slottime: '4 PM', value: '4 PM', status: true }, { slottime: '5 PM', value: '5 PM', status: true }, { slottime: '6 PM', value: '6 PM', status: true },
                { slottime: '7 PM', value: '7 PM', status: true }, { slottime: '8 PM', value: '8 PM', status: true }, { slottime: '9 PM', value: '9 PM', status: true },
                { slottime: '10 PM', value: '10 PM', status: true }, { slottime: '11 PM', value: '11 PM', status: true }
            ],
            days: [
                { dayname: 'saturday', value: 'Saturday' },
                { dayname: 'sunday', value: 'Sunday' },
                { dayname: 'monday', value: 'Monday' },
                { dayname: 'tuesday', value: 'Tuesday' },
                { dayname: 'wednesday', value: 'Wednesday' },
                { dayname: 'thursday', value: 'Thursday' },
                { dayname: 'friday', value: 'Friday' }
            ],
            url: baseUrl,
            isSubmiting: false,
            isLoading: false,
            validation_error: {},
        }
    },
    methods:{
        async getSubAssetComp(){
            try{
                this.isLoading = true
                const token = await this.getUserToken()
                await axios.get(`${apiUrl}backendapi/sub-asset-component`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.subassetescomp = response.data
                    this.isLoading = false
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
            this.isLoading = false
        },
        async storeTable(){
                this.isSubmiting = true
                const token = await this.getUserToken()
            await axios.post(`${apiUrl}backendapi/table`, this.table, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((result) => {
                    if(result.status == 201){
                        this.isSubmiting = false
                        this.successMessage({status:'success',message:'Table Created Successful'})
                        // window.location.href = baseUrl+'sub-asset-component'
                        $("#tableModal").modal('hide');
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
                this.isSubmiting = false
        },
        async getUserAsset() {
            try {
                this.isLoading = true
                const token = await this.getUserToken()
                axios.get(`${apiUrl}backendapi/asset`, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data
                        this.isLoading = false
                    }).catch(error => {
                        console.log(error)
                    })
            } catch (e) {
                console.log(e)
            }
            this.isLoading = false
        },
        async getSubAssetByAsset() {
            try {
                this.isLoading = true
                const token = await this.getUserToken()
                this.subassets = []
                axios.get(`${apiUrl}backendapi/sub-asset?assetId=${this.updateComponent.assetId}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    })
                    .then(response => {
                        this.subassets = response.data
                        this.isLoading = false
                    }).catch(error => {
                        console.log(error)
                    })
            } catch (e) {
                console.log(e)
            }
            this.isLoading = false
        },
        async prepareSlotData() {
            const transformedData = this.slotdev.map(day => {
                const dayName = day.dayname;
                const slots = [];
                for (let i = 0; i < day.sl.length; i++) {
                    const slotTime = day.sl[i];
                    slots.push({
                        slottime: slotTime,
                        status: true
                    });
                }
                return {
                    [dayName]: slots
                };
            });
            this.updateComponent.slot = [...transformedData]
        },
        async preperData(compdata){
            this.updateComponent.id = compdata.id
            this.updateComponent.assetId = compdata.assetId
            this.updateComponent.subAssetId = compdata.subAssetId
            this.updateComponent.listingName = compdata.listingName
            this.updateComponent.type = compdata.type
            this.updateComponent.isEvent = compdata.isEvent
            if(compdata.isEvent){
                this.updateComponent.event = {
                    expireDate: compdata.event.expireDate,
                    eventName: compdata.event.eventName,
                    eventIcon: compdata.event.eventIcon,
                    slot: compdata.event.slot
                }
            }
            // slot: [],
            this.updateComponent.cuisines = compdata.cuisines

            this.slotdev = compdata.slot.map((item) => {
                for (const [key, value] of Object.entries(item)) {
                    let dt = value.map(v => v.slottime)
                    return {
                            dayname: key, sl: [...dt]
                        }
                }
            })

            this.updateComponent.offday = compdata.offday.map(item => {
                return {
                        dayname: item.dayname, from: item.from, to: item.to
                    }
            })
            this.updateComponent.image = compdata.image.map(item => {
                return {
                        link: item.link, precedence: item.precedence
                    }
            })
            this.updateComponent.pricingId = compdata.prices[0].id
            this.updateComponent.pricing = compdata.prices[0].pricing.map(item => {
                return {itemName: 'Demo', image: item.image, qty: 1, size: '120 cm', weight: '250 gm',
                price:0,description:'Here is demo description.'}
            })

            this.updateComponent.table = compdata.tables.map(item => {
                return {
                    id:item.id,capacity: item.capacity, type: item.type, position: item.position, size: item.size,
                    ryservable: item.ryservable.toString(),splitable: item.splitable.toString(), status: item.status.toString()
                }
            })
            this.updateComponent.description = compdata.description
            this.updateComponent.terms = compdata.terms
            this.updateComponent.reservationCategory = compdata.reservationCategory
            this.updateComponent.status = compdata.status
            // table: []
        },
        async editSubAssetComp(compdata){
            this.clearForm()
            await this.getUserAsset()
            await this.preperData(compdata)
            // console.log(this.updatesubasset)
            $("#updateSubAssetCompModal").modal('show');
        },
        async updateSubAssetComp(){
            this.isSubmiting = true
            const token = await this.getUserToken()
            // return ;
            await this.prepareSlotData()
            // delete this.updatesubasset['businessId'];
            axios.put(`${apiUrl}backendapi/sub-asset-component?id=${this.updateComponent.id}`, this.updateComponent, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
                .then((result) => {
                    if(result.status == 200){
                        this.isSubmiting = false
                        // $("#updateSubAssetCompModal").modal('hide');
                        // this.successMessage({status:'success',message:'Sub Asset Component Updated Successful'})
                        // this.getSubAssetComp()
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
                this.isSubmiting = false
        },
        async addATable(compo) {
            this.table.subAssetCompId = compo.id
            $("#tableModal").modal('show');
        },
        addMoreImage() {
            this.updateComponent.image.push({link:'',precedence:this.updateComponent.image.length})
        },
        removeImageChild(index) {
            if (index == 0) return;
            this.updateComponent.image.splice(index, 1);
        },
        addMoreMenu(){
            this.updateComponent.pricing.push({id:'', itemName: 'Demo', image: '',qty: 1, size: '120 cm', weight: '250 gm', price:0,description:'Here is demo description.'})
        },
        removeMenuChild(index) {
            if (index == 0) return;
            this.updateComponent.pricing.splice(index, 1);
        },
        addMoreSlot() {
            if(this.slotdev.length == 7) return ;
            this.slotdev.push({ dayname: '', sl: [] })
        },
        removeSlotChild(index) {
            if (index == 0) return;
            this.slotdev.splice(index, 1);
        },
        addMoreOffday() {
            this.updateComponent.offday.push({ dayname: '', from: '', to: '' })
        },
        removeOffdayChild(index) {
            if (index == 0) return;
            this.updateComponent.offday.splice(index, 1);
        },
        addMoreTable() {
            this.updateComponent.table.push({id:'', capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" })
        },
        removeTableChild(index) {
            if (index == 0) return;
            this.updateComponent.table.splice(index, 1);
        },
        async clearForm(){
            this.table = {
                subAssetCompId: '',
                capacity: '',
                type: '',
                position: '',
                size: '',
                splitable: 'true',
                ryservable: 'true',
                status: 'true'
            },
            this.updateComponent = {
                id: '',
                assetId: '',
                subAssetId: '',
                listingName: '',
                type: '',
                isEvent: false,
                event: {
                    expireDate: '',
                    eventName:'',
                    eventIcon:'',
                    slot:''
                },
                slot: [],
                offday: [{ dayname: '', from: '', to: '' }],
                pricing: [{ id:'', itemName: 'Demo', image: '',qty: 1, size: '120 cm', weight: '250 gm', price:0,description:'Here is demo description.'}],
                description: '',
                terms: '',
                reservationCategory: '',
                image: [{link:'',precedence:0}],
                status: "true",
                table: [{id: '',capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" }]
            }
        }
    },
    watch: {
        'updateComponent.isEvent': function (newIsEvent) {
            if (!newIsEvent) {
                this.updateComponent.event = {
                expireDate: '',
                eventName: '',
                eventIcon: '',
                slot: ''
                };
            }
        }
    },
    computed: {
        showPermission() {
            return this.getUserPermission();
        }
    },
    mounted(){
        this.getSubAssetComp()
    }
}
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Listing Type</h4>
                <a v-if="showPermission.includes('listing-type-create')" href="create/sub-asset-component"
                    class="btn btn-primary mb-2 mr-3"
                >
                    Create Listing Type
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
                                <th>Name</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Branch</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" v-if="showPermission.includes('listing-type-edit')">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="subassetescomp && subassetescomp.length > 0">
                        <template v-for="(subassetcom,index) in subassetescomp" :key="subassetcom.id">
                            <tr>
                                <td>{{ index+1 }}</td>
                                <td>{{ subassetcom.listingName }}</td>
                                <td>{{ subassetcom.type }}</td>
                                <td>{{ subassetcom.reservationCategory }}</td>
                                <td>{{ subassetcom.asset.propertyName }}</td>
                                <td class="text-center">
                                    <span>{{ subassetcom.status == 1 ? 'Active' : 'Deactive' }}</span>
                                </td>
                                <td  v-if="showPermission.includes('listing-type-edit')">
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" type="button" title="Add Table" @click="addATable(subassetcom)">
                                        <div class="icon-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg><span class="icon-name"></span>
                                        </div>
                                    </a></li>
                                    <li><a  v-if="showPermission.includes('listing-type-edit')" href="javascript:void(0);" @click="editSubAssetComp(subassetcom)" type="button" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                    </a></li>

                                    <!--<li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                    <li><a href="subasset" title="Sub Asset">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a></li> -->
                                </ul>
                            </td>
                            </tr>
                        </template>
                    </tbody>
                    <tbody v-else>
                            <tr class="text-center text-bold">
                                <td colspan="7">No Data Found</td>
                            </tr>
                        </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="tableModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Add New Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content widget-content-area">
                            <form @submit.prevent="storeTable()">
                                <div class="form-row">
                                <div class="col-12">
                                    <label for="Capacity">Capacity</label>
                                    <input type="number" class="form-control form-control-sm" id="Capacity" placeholder="Capacity" v-model="table.capacity" >
                                </div>
                                <div class="col-12 mt-1">
                                <label for="table_type">Type</label>
                                <select id="table_type" class="form-control" v-model="table.type">
                                    <option value="">Choose Type...</option>
                                    <option value="KING">KING</option>
                                    <option value="MEDIUM">MEDIUM</option>
                                    <option value="LARGE">LARGE</option>
                                    <option value="SINGLE">SINGLE</option>
                                    <option value="TWIN">TWIN</option>
                                    <option value="QUEEN">QUEEN</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('type')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.type[0] }}
                                </div>
                            </div>
                                <div class="col-12 mt-1">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control form-control-sm" id="position" placeholder="Ex:Last Right Corner" v-model="table.position" >
                                </div>
                                <div class="col-12 mt-1">
                                    <label for="size">Size</label>
                                    <input type="text" class="form-control form-control-sm" id="size" placeholder="Ex: 50cm x 88cm" v-model="table.size" >
                                </div>
                                <div class="col-12 mt-1">
                                <label for="Splitable">Splittable</label>
                                    <select id="Splitable" class="form-control" v-model="table.splitable">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-1">
                                <label for="ryservable">Reservable</label>
                                    <select id="ryservable" class="form-control" v-model="table.ryservable">
                                        <option value="true">Yes</option>
                                        <option value="false">No</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-1">
                                <label for="siz-status">Status</label>
                                    <select id="status" class="form-control" v-model="table.status">
                                        <option value="true">Active</option>
                                        <option value="false">Deactive</option>
                                    </select>
                                </div>
                            </div>

                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="clearForm"></i> Discard</button>

                                    <button type="submit" class="btn btn-primary">
                                        <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>Submit</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="updateSubAssetCompModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog" v-if="updateComponent.id">
            <div class="modal-dialog modal-xl">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update {{ updateComponent.listingName }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" method="post" @submit.prevent="updateSubAssetComp()" id="update-SubAssetComp-form">
            <div class="row">
                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                    <div class="statbox widget box">
                        <div class="widget-content">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="service_type">Type</label>
                                    <select id="service_type" class="form-control" v-model="updateComponent.type">
                                        <option value="">Choose Asset Type...</option>
                                        <option value="HOTEL">HOTEL</option>
                                        <option value="RESTAURANT">RESTAURANT</option>
                                        <option value="SERVICE_APARTMENT">SERVICE APARTMENT</option>
                                        <option value="MOVIE_THEATER">MOVIE THEATER</option>
                                        <option value="SPA">SPA</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('type')" class="text-danger font-weight-bold">
                                        {{ validation_error.type[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="service_type">Category</label>
                                    <select id="service_type" class="form-control" v-model="updateComponent.reservationCategory">
                                        <option value="">Choose Asset Type...</option>
                                        <option value="ROOM">ROOM</option>
                                        <option value="TABLE">TABLE</option>
                                        <option value="TICKET">TICKET</option>
                                        <option value="APPOINTMENT">APPOINTMENT</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('reservationCategory')" class="text-danger font-weight-bold">
                                        {{ validation_error.reservationCategory[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="name">Restaurant Name</label>
                                    <input type="text" class="form-control form-control-sm" id="name" placeholder="Restaurant Name" v-model="updateComponent.listingName">
                                    <div v-if="validation_error.hasOwnProperty('listingName')" class="text-danger font-weight-bold">
                                        {{ validation_error.listingName[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business_type">Select Asset</label>
                                    <select id="business_type" class="form-control" v-model="updateComponent.assetId" @change="getSubAssetByAsset()">
                                        <option value="">Choose Asset...</option>
                                        <option v-for="(asset,ind) in assets" :key="ind" :value="asset.id">{{asset.propertyName}}</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('assetId')" class="text-danger font-weight-bold">
                                        {{ validation_error.assetId[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business_type">Select Listing</label>
                                    <select id="business_type" class="form-control" v-model="updateComponent.subAssetId">
                                        <option value="">Choose Listing...</option>
                                        <option v-for="(subasset,ind) in subassets" :key="ind" :value="subasset.id">floor:{{subasset.floor}},sqft:{{subasset.sqft}},{{ subasset.address }}</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('subAssetId')" class="text-danger font-weight-bold">
                                        {{ validation_error.subAssetId[0] }}
                                    </div>
                                </div>

                                <!-- <div class="form-group col-md-4">
                                    <label for="image">Banner Image Link</label>
                                    <input type="text" class="form-control form-control-sm" id="image" placeholder="Banner Image" v-model="updateComponent.image">
                                    <div v-if="validation_error.hasOwnProperty('image')" class="text-danger font-weight-bold">
                                        {{ validation_error.image[0] }}
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex">
                            <h5>Event</h5>
                            <div class="d-flex mx-2">
                                <div class="billing-cycle-radios">
                                    <div class="radio billed-yearly-radio">
                                        <div class="d-flex justify-content-center">
                                            <!-- <span class="txt-monthly mr-2">Has Event?</span> -->
                                            <label class="switch s-icons s-outline  s-outline-primary">
                                                <input v-model="updateComponent.isEvent" :checked="updateComponent.isEvent"  type="checkbox" id="Event">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="row" v-if="updateComponent.isEvent">
                            <div class="form-group col-md-4">
                                <label for="expireDate">Expire Date</label>
                                <input type="date" class="form-control form-control-sm" id="expireDate" placeholder="Expire Date" v-model="updateComponent.event.expireDate" required>
                                <div v-if="validation_error.hasOwnProperty('expireDate')" class="text-danger font-weight-bold">
                                    {{ validation_error.expireDate[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="eventName">Event Name</label>
                                <input type="text" class="form-control form-control-sm" id="eventName" placeholder="Event Name" v-model="updateComponent.event.eventName" required>
                                <div v-if="validation_error.hasOwnProperty('eventName')" class="text-danger font-weight-bold">
                                    {{ validation_error.eventName[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="eventIcon">Event Icon</label>
                                <input type="text" class="form-control form-control-sm" id="eventIcon" placeholder="Event Name" v-model="updateComponent.event.eventIcon" required>
                                <div v-if="validation_error.hasOwnProperty('eventIcon')" class="text-danger font-weight-bold">
                                    {{ validation_error.eventIcon[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <select id="event-slot" class="form-control form-control-sm" v-model="updateComponent.event.slot" required>
                                    <option value="">Choose Slot...</option>
                                    <option v-for="(value,ind) in makeSlot" :value="value.value" :key="ind">{{ value.value }}</option>
                                </select>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row">
                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                    <div class="statbox widget box ">
                        <h5>Banner Images</h5>
                        <div class="widget-content ">
                            <div class="row text-center my-1">
                                <div class="col-10  text-success">
                                    <b>Image Link</b>
                                </div>

                                <div class="col-2  text-danger">
                                    <b>Remove</b>
                                </div>
                            </div>
                            <div class="row" v-for="(img,ind) in updateComponent.image" :key="ind">
                                <div class="form-group col-md-10">
                                    <input type="text" class="form-control form-control-sm" :id="ind" v-model="img.link" placeholder="Banner Image" required>
                                </div>
                            <div class="form-group form-control-sm col-md-2 text-center">
                                    <a
                                    href="javascript:void(0)"
                                    @click.prevent="removeImageChild(ind)"
                                    class="mt-5"
                                    ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                </div>
                        </div>
                    <a
                    href="javascript:void(0)"
                    @click.prevent="addMoreImage()"
                    class="btn btn-warning"
                >Add More
                </a>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                    <div class="statbox widget box ">
                        <h5>Create Schedule</h5>
                        <div class="widget-content ">
                            <div class="row text-center my-1">
                                <div class="col-2 col-sm-2 text-success">
                                    <b>Day</b>
                                </div>

                                <div class="col-8  text-success">
                                    <b>Slot</b>
                                </div>

                                <div class="col-2  text-danger">
                                    <b>Remove</b>
                                </div>
                            </div>
                            <div class="row" v-for="(sched,ind) in slotdev" :key="ind">
                                <div class="form-group col-md-2 col-sm-2">
                                    <select id="product-category" class="form-control form-control-sm" v-model="sched.dayname">
                                        <option value="">Choose Day...</option>
                                        <option v-for="value in days" :value="value.dayname" :key="value.dayname"><p style="text-transform: capitalize;">{{ value.value }}</p></option>
                                    </select>
                                </div>
                                <div class="form-group col-md-8">
                                    <Multiselect v-model="sched.sl" mode="tags" placeholder="Select Slot" track-by="slottime" label="slottime" :close-on-select="false" :search="true" :options="makeSlot" :searchable="true">
                                        <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                        <div
                                            class="multiselect-tag is-user"
                                            :class="{
                                            'is-disabled': disabled
                                            }"
                                        >
                                            {{ option.slottime }}
                                            <span
                                            v-if="!disabled"
                                            class="multiselect-tag-remove"
                                            @mousedown.prevent="handleTagRemove(option, $event)"
                                            >
                                            <span class="multiselect-tag-remove-icon"></span>
                                            </span>
                                        </div>
                                        </template>
                                    </Multiselect>
                            </div>
                            <div class="form-group form-control-sm col-md-2 text-center">
                                    <a
                                    href="javascript:void(0)"
                                    @click.prevent="removeSlotChild(ind)"
                                    class="mt-5"
                                    ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                </div>
                        </div>
                    <a
                    href="javascript:void(0)"
                    @click.prevent="addMoreSlot()"
                    class="btn btn-warning"
                >Add More
                </a>
                    </div>
                    <div class="widget-content mt-5">
                        <h5>Choose Offday</h5>
                        <div class="row text-center my-1">
                                <div class="col-2 col-sm-2 text-success">
                                    <b>Day</b>
                                </div>

                                <div class="col-4  text-success">
                                    <b>From</b>
                                </div>
                                <div class="col-4  text-success">
                                    <b>To</b>
                                </div>

                                <div class="col-2  text-danger">
                                    <b>Remove</b>
                                </div>
                            </div>
                        <div class="row" v-for="(offd,ind) in updateComponent.offday" :key="ind">
                            <div class="form-group col-md-2 col-sm-2">
                                <select id="product-category" class="form-control form-control-sm" v-model="offd.dayname">
                                    <option value="">Choose Offday...</option>
                                    <option v-for="value in days" :value="value.dayname" :key="value.dayname">{{ value.value }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select id="product-category" class="form-control form-control-sm" v-model="offd.from">
                                    <option value="">From</option>
                                    <option v-for="value in makeSlot" :value="value.slottime" :key="value.slottime">{{ value.value }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select id="product-category" class="form-control form-control-sm" v-model="offd.to">
                                    <option value="">To</option>
                                    <option v-for="value in makeSlot" :value="value.slottime" :key="value.slottime">{{ value.value }}</option>
                                </select>
                            </div>
                            <div class="form-group form-control-sm col-md-2 text-center">
                                    <a
                                    href="javascript:void(0)"
                                    @click.prevent="removeOffdayChild(ind)"
                                    class="mt-5"
                                    ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                </div>
                        </div>
                    <a
                    href="javascript:void(0)"
                    @click.prevent="addMoreOffday()"
                    class="btn btn-warning"
                >Add More
                </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <h5 class="mb-2">About</h5>
                                <textarea class="form-control form-control-sm" id="about" placeholder="About Restaurant" v-model="updateComponent.description" rows="3"></textarea>
                                <div
                                    v-if="validation_error.hasOwnProperty('description')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.description[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-12 mt-2">
                                <h5 class="mb-2">Terms & Conditions</h5>
                                <textarea class="form-control form-control-sm" id="terms" placeholder="Write Terms & Conditions" v-model="updateComponent.terms" rows="4"></textarea>
                                <div
                                    v-if="validation_error.hasOwnProperty('terms')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.terms[0] }}
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
                            <div class="form-group col-md-12">
                                <h5 class="mb-2">Menu Link</h5>
                                    <div class="widget-content ">
                                        <div class="row text-center my-1">
                                            <div class="col-10  text-success">
                                                <b>Image Link</b>
                                            </div>

                                            <div class="col-2  text-danger">
                                                <b>Remove</b>
                                            </div>
                                        </div>
                                        <div class="row" v-for="(price,ind) in updateComponent.pricing" :key="ind">
                                            <div class="form-group col-md-10">
                                                <input type="text" class="form-control form-control-sm" :id="ind" v-model="price.image" placeholder="Menu Image URL" required>
                                            </div>
                                        <div class="form-group form-control-sm col-md-2 text-center">
                                                <a
                                                href="javascript:void(0)"
                                                @click.prevent="removeMenuChild(ind)"
                                                class="mt-5"
                                                ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                            </div>
                                    </div>
                                        <a
                                        href="javascript:void(0)"
                                        @click.prevent="addMoreMenu()"
                                        class="btn btn-warning"
                                    >Add More
                                    </a>
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
                            <div class="form-group col-md-12">
                                <h5 class="mb-2">Cuisine</h5>
                                <Multiselect v-model="updateComponent.cuisines" mode="tags" placeholder="Select Cuisine" track-by="name" label="name" :close-on-select="false" :search="true" :options="cuisineList" :searchable="true">
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
                                    </Multiselect>
                                <div
                                    v-if="validation_error.hasOwnProperty('cuisine')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.cuisine[0] }}
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
                    <h4>Create Table</h4>
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content ">
                            <div class="row text-center my-1">
                                <div class="col-2 col-sm-2 text-success">
                                    <b>Type</b>
                                </div>
                                <div class="col-1  text-success">
                                    <b>Capacity</b>
                                </div>
                                <div class="text-success col-3">
                                    <b>Position</b>
                                </div>
                                <div class="col-3  text-success">
                                    <b>Size</b>
                                </div>
                                <div class="col-1  text-success">
                                    <b>Splittable</b>
                                </div>
                                <div class="col-1  text-success">
                                    <b>Reservable</b>
                                </div>
                                <div class="col-1  text-danger">
                                    <b>Remove</b>
                                </div>
                            </div>
                            <div class="row" v-for="(qt,index) in updateComponent.table" :key="index">
                                <div class="form-group col-md-2">
                                <select id="table_type" class="form-control" v-model="qt.type">
                                    <option value="">Choose Type...</option>
                                    <option value="KING">KING</option>
                                    <option value="MEDIUM">MEDIUM</option>
                                    <option value="LARGE">LARGE</option>
                                    <option value="SINGLE">SINGLE</option>
                                    <option value="TWIN">TWIN</option>
                                    <option value="QUEEN">QUEEN</option>
                                    <option value="OTHERS">OTHERS</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <input type="number" class="form-control form-control-sm" id="capacity" placeholder="Capacity" v-model="qt.capacity" >
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" class="form-control form-control-sm" id="position" placeholder="Ex:Last Right Corner" v-model="qt.position" >
                            </div>
                            <div class="form-group col-md-3">
                                <input type="text" class="form-control form-control-sm" id="size" placeholder="Ex: 50cm x 88cm" v-model="qt.size" >
                            </div>
                            <div class="form-group col-md-1">
                                <select id="split" class="form-control" v-model="qt.splitable">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-1">
                                <select id="reservable" class="form-control" v-model="qt.ryservable">
                                    <option value="true">Yes</option>
                                    <option value="false">No</option>
                                </select>
                            </div>
                                <div class="form-group form-control-sm col-md-1 text-center">
                                    <a
                                    href="javascript:void(0)"
                                    @click.prevent="removeTableChild(index)"
                                    class="mt-5"
                                    ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                </div>
                            </div>
                        </div>
                        <a
                                href="javascript:void(0)"
                                @click.prevent="addMoreTable()"
                                class="btn btn-warning"
                            >Add More
                            </a>
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
    max-width: 86%;
}
</style>
