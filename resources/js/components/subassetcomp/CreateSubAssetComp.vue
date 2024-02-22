<script>
import axios from 'axios';
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    components: {
    },

    data(){
        return {
            subassetcomp : {
                assetId: '',
                subAssetId: '',
                listingName: '',
                type: '',
                slot: [
                    {
                    "sunday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "monday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "tuesday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "wednesday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "thursday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    }
                ],
                offday: [
                    {
                    "dayname": "friday",
                    "from": "9 AM",
                    "to": "3 PM"
                    },
                    {
                    "dayname": "saturday",
                    "from": "9 AM",
                    "to": "4 PM"
                    }
                ],
                description: '',
                reservationCategory: '',
                image: '',
                status: true
            },
            assets: [],
            subassets: [],
            validation_error: {},
        }
    },
    methods: {
        createSubAssetComp(){
            const tok = localStorage.getItem('authuser')
            const token = JSON.parse(tok)
            axios.post(`${apiUrl}backendapi/sub-asset-component`, this.subassetcomp, {
                headers: {
                    'Authorization': `Bearer ${token.token}`
                }
            })
                .then((result) => {
                    if(result.status == 201){
                        this.successMessage({status:'success',message:'Sub Asset Component Created Successful'})
                        window.location.href = baseUrl+'sub-asset-component'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        getUserAsset(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                 axios.get(`${apiUrl}backendapi/asset`,{
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

        getSubAssetByAsset(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                this.subassets = []
                 axios.get(`${apiUrl}backendapi/sub-asset?assetId=${this.subassetcomp.assetId}`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then(response => {
                    this.subassets = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },


        clearForm() {
            this.subasset = {
                assetId: '',
                subAssetId: '',
                listingName: '',
                type: '',
                slot: [
                    {
                    "sunday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "monday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "tuesday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "wednesday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    },
                    {
                    "thursday": [
                        {
                        "slottime": "9 AM",
                        "status": true
                        },
                        {
                        "slottime": "10 AM",
                        "status": true
                        },
                        {
                        "slottime": "11 AM",
                        "status": true
                        },
                        {
                        "slottime": "12 PM",
                        "status": true
                        },
                        {
                        "slottime": "2 PM",
                        "status": true
                        },
                        {
                        "slottime": "3 PM",
                        "status": true
                        },
                        {
                        "slottime": "4 PM",
                        "status": true
                        }
                    ]
                    }
                ],
                offday: [
                    {
                    "dayname": "friday",
                    "from": "9 AM",
                    "to": "3 PM"
                    },
                    {
                    "dayname": "saturday",
                    "from": "9 AM",
                    "to": "4 PM"
                    }
                ],
                description: '',
                reservationCategory: '',
                image: '',
                status: true
            }
            this.validation_error = {}

        },
    },
    mounted(){
        this.getUserAsset()
    }
}
</script>
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
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Restaurant Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" placeholder="Restaurant Name" v-model="subassetcomp.listingName" >
                                <div
                                    v-if="validation_error.hasOwnProperty('listingName')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.listingName[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="business_type">Select Asset</label>
                                <select id="business_type" class="form-control" v-model="subassetcomp.assetId" @change="getSubAssetByAsset()">
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
                                <label for="business_type">Select Sub Asset</label>
                                <select id="business_type" class="form-control" v-model="subassetcomp.subAssetId">
                                    <option value="">Choose Sub Asset Id...</option>
                                    <option v-for="(subasset,ind) in subassets" :key="ind" :value="subasset.id">floor:{{subasset.floor}},sqft:{{subasset.sqft}}</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('subAssetId')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.subAssetId[0] }}
                                </div>
                            </div>

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
                                <div
                                    v-if="validation_error.hasOwnProperty('type')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.type[0] }}
                                </div>
                            </div>

                            <div class="form-group col-md-8">
                                <label for="about">About</label>
                                <input type="text" class="form-control form-control-sm" id="about" placeholder="About Restaurant" v-model="subassetcomp.description" >
                                <div
                                    v-if="validation_error.hasOwnProperty('description')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.description[0] }}
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
                            <div class="form-group col-md-4">
                                <label for="service_type">Category</label>
                                <select id="service_type" class="form-control" v-model="subassetcomp.reservationCategory">
                                    <option value="">Choose Asset Type...</option>
                                    <option value="ROOM">ROOM</option>
                                    <option value="TABLE">TABLE</option>
                                    <option value="TICKET">TICKET</option>
                                    <option value="APPOINTMENT">APPOINTMENT</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('reservationCategory')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.reservationCategory[0] }}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="image">Banner Image Link</label>
                                <input type="text" class="form-control form-control-sm" id="image" placeholder="Banner Image" v-model="subassetcomp.image" >
                                <div
                                    v-if="validation_error.hasOwnProperty('image')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.image[0] }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
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
