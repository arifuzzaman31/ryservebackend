<script>
import axios from 'axios';
import Mixin from '../../mixer'
import Multiselect from '@vueform/multiselect'
export default {
    mixins: [Mixin],
    components: {
        Multiselect
    },

    data() {
        return {
            subassetcomp: {
                assetId: '',
                subAssetId: '',
                listingName: '',
                type: '',
                slot: [],
                offday: [{ dayname: '', from: '', to: '' }],
                pricing: [{ itemName: 'Demo', image: '',qty: 1, size: '120 cm', weight: '250 gm', price:0,description:'Here is demo description.'}],
                description: '',
                reservationCategory: '',
                image: '',
                status: "true",
                table: [{ capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" }]
            },
            assets: [],
            subassets: [],
            slotdev: [{ dayname: '', sl: [] }],
            makeSlot: [{ slottime: '7 AM', value: '7 AM', status: true }, { slottime: '8 AM', value: '8 AM', status: true }, { slottime: '9 AM', value: '9 AM', status: true },
                { slottime: '10 AM', value: '10 AM', status: true }, { slottime: '11 AM', value: '11 AM', status: true }, { slottime: '12 PM', value: '12 PM', status: true },
                { slottime: '1 PM', value: '1 PM', status: true }, { slottime: '2 PM', value: '2 PM', status: true }, { slottime: '3 PM', value: '3 PM', status: true },
                { slottime: '4 PM', value: '4 PM', status: true }, { slottime: '5 PM', value: '5 PM', status: true }, { slottime: '6 PM', value: '6 PM', status: true },
                { slottime: '7 PM', value: '7 PM', status: true }, { slottime: '8 PM', value: '8 PM', status: true }, { slottime: '9 PM', value: '9 PM', status: true },
                { slottime: '10 PM', value: '10 PM', status: true }, { slottime: '11 PM', value: '11 PM', status: true }
            ],
            days: [
                { dayname: 'satarday', value: 'Satarday' },
                { dayname: 'sunday', value: 'Sunday' },
                { dayname: 'monday', value: 'Monday' },
                { dayname: 'tuesday', value: 'Tuesday' },
                { dayname: 'wednesday', value: 'Wednesday' },
                { dayname: 'thursday', value: 'Thursday' },
                { dayname: 'friday', value: 'Friday' }
            ],
            validation_error: {},
        }
    },
    methods: {
        async prepareData() {
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
            this.subassetcomp.slot = [...transformedData]
        },
        async createSubAssetComp() {
            const tok = localStorage.getItem('authuser')
            const token = JSON.parse(tok)
            this.prepareData()
            await axios.post(`${apiUrl}backendapi/sub-asset-component`, this.subassetcomp, {
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then((result) => {
                    if (result.status == 201) {
                        this.successMessage({ status: 'success', message: 'Sub Asset Component Created Successful' })
                        window.location.href = baseUrl + 'sub-asset-component'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        getUserAsset() {
            try {
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                axios.get(`${apiUrl}backendapi/asset`, {
                        headers: {
                            'Authorization': `Bearer ${token.token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data
                    }).catch(error => {
                        console.log(error)
                    })
            } catch (e) {
                console.log(e)
            }
        },

        getSubAssetByAsset() {
            try {
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                this.subassets = []
                axios.get(`${apiUrl}backendapi/sub-asset?assetId=${this.subassetcomp.assetId}`, {
                        headers: {
                            'Authorization': `Bearer ${token.token}`
                        }
                    })
                    .then(response => {
                        this.subassets = response.data
                    }).catch(error => {
                        console.log(error)
                    })
            } catch (e) {
                console.log(e)
            }
        },
        addMoreSlot() {
            this.slotdev.push({ dayname: '', sl: [] })
        },
        removeSlotChild(index) {
            if (index == 0) return;
            this.slotdev.splice(index, 1);
        },
        addMoreOffday() {
            this.subassetcomp.offday.push({ dayname: '', from: '', to: '' })
        },
        removeOffdayChild(index) {
            if (index == 0) return;
            this.subassetcomp.offday.splice(index, 1);
        },
        addMoreTable() {
            this.subassetcomp.table.push({ capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" })
        },
        removeTableChild(index) {
            if (index == 0) return;
            this.subassetcomp.table.splice(index, 1);
        },
        clearForm() {
            this.subasset = {
                assetId: '',
                subAssetId: '',
                listingName: '',
                type: '',
                slot: [],
                offday: [{ dayname: '', from: '', to: '' }],
                pricing: [{ itemName: 'Demo', image: '',qty: 1, size: '120 cm',
                weight: '250 gm', price:0,description:'Here is demo description.'}],
                description: '',
                reservationCategory: '',
                image: '',
                status: "true",
                table: [{ capacity: 1, type: '', position: '', size: '', ryservable: "true", splitable: "true", status: "true" }]
            }
            this.validation_error = {}

        },
    },
    mounted() {
        this.getUserAsset()
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css">

</style>

<template>
    <div class="widget-header">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                <h4>Create New Sub Asset Component</h4>
            </div>
        </div>

        <form class="needs-validation" method="post" @submit.prevent="createSubAssetComp()" id="add-SubAssetComp-form">
            <div class="row">
                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                    <div class="statbox widget box">
                        <div class="widget-content">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="service_type">Type</label>
                                    <select id="service_type" class="form-control" v-model="subassetcomp.type">
                                        <option value="">Choose Asset Type...</option>
                                        <option value="HOTEL">HOTEL</option>
                                        <option value="RESTAURANT">RESTAURANT</option>
                                        <option value="SERVICE_APARTMENT">SERVICE_APARTMENT</option>
                                        <option value="MOVIE_THEATER">MOVIE_THEATER</option>
                                        <option value="SPA">SPA</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('type')" class="text-danger font-weight-bold">
                                        {{ validation_error.type[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="service_type">Category</label>
                                    <select id="service_type" class="form-control" v-model="subassetcomp.reservationCategory">
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
                                    <input type="text" class="form-control form-control-sm" id="name" placeholder="Restaurant Name" v-model="subassetcomp.listingName">
                                    <div v-if="validation_error.hasOwnProperty('listingName')" class="text-danger font-weight-bold">
                                        {{ validation_error.listingName[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="business_type">Select Asset</label>
                                    <select id="business_type" class="form-control" v-model="subassetcomp.assetId" @change="getSubAssetByAsset()">
                                        <option value="">Choose Asset...</option>
                                        <option v-for="(asset,ind) in assets" :key="ind" :value="asset.id">{{asset.propertyName}}</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('assetId')" class="text-danger font-weight-bold">
                                        {{ validation_error.assetId[0] }}
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="business_type">Select Sub Asset</label>
                                    <select id="business_type" class="form-control" v-model="subassetcomp.subAssetId">
                                        <option value="">Choose Sub Asset Id...</option>
                                        <option v-for="(subasset,ind) in subassets" :key="ind" :value="subasset.id">floor:{{subasset.floor}},sqft:{{subasset.sqft}},{{ subasset.address }}</option>
                                    </select>
                                    <div v-if="validation_error.hasOwnProperty('subAssetId')" class="text-danger font-weight-bold">
                                        {{ validation_error.subAssetId[0] }}
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="image">Banner Image Link</label>
                                    <input type="text" class="form-control form-control-sm" id="image" placeholder="Banner Image" v-model="subassetcomp.image">
                                    <div v-if="validation_error.hasOwnProperty('image')" class="text-danger font-weight-bold">
                                        {{ validation_error.image[0] }}
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
                        <div class="row" v-for="(offd,ind) in subassetcomp.offday" :key="ind">
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
                                <!-- <input type="text" class="form-control form-control-sm" id="about" placeholder="About Restaurant" v-model="subassetcomp.description" > -->
                                <textarea class="form-control form-control-sm" id="about" placeholder="About Restaurant" v-model="subassetcomp.description" rows="3"></textarea>
                                <div
                                    v-if="validation_error.hasOwnProperty('description')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.description[0] }}
                                </div>
                            </div>

                            <!-- <div class="form-group col-md-4">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" v-model="subassetcomp.status">
                                    <option value="true">Active</option>
                                    <option value="false">Deactive</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('status')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.status[0] }}
                                </div>
                            </div> -->
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
                                <h5 class="mb-2">Pricing Link</h5>
                                <input type="text" class="form-control form-control-sm" id="Pricing-Link" placeholder="Add Pricing Link" v-model="subassetcomp.pricing[0].image" >
                                <div
                                    v-if="validation_error.hasOwnProperty('pricing')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.pricing[0] }}
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
                                    <b>Splitable</b>
                                </div>
                                <div class="col-1  text-success">
                                    <b>Ryservable</b>
                                </div>
                                <div class="col-1  text-danger">
                                    <b>Remove</b>
                                </div>
                            </div>
                            <div class="row" v-for="(qt,index) in subassetcomp.table" :key="index">
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

        <button class="btn btn-success mt-1 btn-lg" type="submit">Save</button>
    </form>
</div>
</template>

