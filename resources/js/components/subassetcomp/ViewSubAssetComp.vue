<script>
import axios from 'axios';
import Mixin from '../../mixer'
export default {
    mixins:[Mixin],
    components:{
    },

    data(){
        return {
            table: {
                subAssetCompId: '',
                capacity: '',
                type: '',
                position: '',
                size: '',
                status: true,
            },
            subassetescomp: [],
            url: baseUrl,
            validation_error: {},
        }
    },
    methods:{
        async getSubAsset(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                await axios.get(`${apiUrl}backendapi/sub-asset-component`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then(response => {
                    this.subassetescomp = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        async storeTable(){
            const tok = localStorage.getItem('authuser')
            const token = JSON.parse(tok)
            await axios.post(`${apiUrl}backendapi/table`, this.table, {
                headers: {
                    'Authorization': `Bearer ${token.token}`
                }
            })
                .then((result) => {
                    if(result.status == 201){
                        this.successMessage({status:'success',message:'Table Created Successful'})
                        // window.location.href = baseUrl+'sub-asset-component'
                    }
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        async addATable(compo) {
            this.table.subAssetCompId = compo.id
            $("#tableModal").modal('show');
        },
        async formReset(){
            this.table = {
                subAssetCompId: '',
                capacity: '',
                type: '',
                position: '',
                size: '',
                status: true,
            }
        }
    },
    mounted(){
        this.getSubAsset()
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
                            <h4>Sub Asset Component</h4>
                <a href="create/sub-asset-component"
                    class="btn btn-primary mb-2 mr-3"
                >
                    Create SubAssetComponent
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
                            <th>Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                <td>
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" type="button" title="Add Table" @click="addATable(subassetcom)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                        </a></li>
                                    <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                    <li><a href="subasset" title="Sub Asset">
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
        <div id="tableModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Add New Table</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
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
                                <label for="siz-status">Status</label>
                                    <select id="status" class="form-control" v-model="table.status">
                                        <option value="true">Active</option>
                                        <option value="false">Deactive</option>
                                    </select>
                                </div>
                            </div>

                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>

                                    <button type="submit" class="btn btn-primary">Submit</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<style scoped>

</style>
