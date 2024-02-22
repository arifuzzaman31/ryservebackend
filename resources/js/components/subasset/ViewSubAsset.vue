<script>
import axios from 'axios';
import Mixin from '../../mixer'
export default {
    mixins:[Mixin],
    components:{
    },

    data(){
        return {
            subassetes: [],
            url: baseUrl,
            validation_error: {},
        }
    },
    methods:{
        async getSubAsset(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                await axios.get(`${apiUrl}backendapi/sub-asset`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
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

    },
    computed: {
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
                            <!-- <th class="text-center">Action</th> -->
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
                                <!-- <td>
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                        </a></li>
                                    <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                    <li><a href="subasset" title="Sub Asset">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a></li>
                                </ul>
                            </td> -->
                            </tr>
                        </template>
                    </tbody>
                </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>


<style scoped>

</style>
