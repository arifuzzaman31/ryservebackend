<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
      
        const fabrics  = ref([]);
        const errors  = ref([]);
        const fabric_id  = ref('');
        const form = reactive({
            fabric_name: '',
            fabric_code: '',
            status: true
        });
        const toastMixin = Swal.mixin({
            toast: true,
            icon: 'success',
            title: 'General Title',
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        const fireToast = async(data) =>{
            toastMixin.fire({
                icon: data.status,
                animation: true,
                title: data.message
            });
        }

        const getFabric = async(page = 1) =>{
            let res = await axios.get(baseUrl+`fabrics/create?page=${page}`);
            fabrics.value = res.data;
            // console.log(fabrics.value)
        }

        const deleteFabric = async(id)=>{
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl+`fabrics/${id}`).then(
                        response => {
                            getFabric()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeFabric = async() =>{
           try{
                await axios.post('fabrics',form).then(
                    response => {
                        fireToast(response.data)
                        $("#fabricModal").modal('hide');
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
                formReset()
                getFabric()
            }catch(e){
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
            }
        }

        const updateFabric = async() =>{
           try{
                await axios.patch('fabrics/' + fabric_id.value,form).then(
                    response => {
                        $("#fabricModal").modal('hide');
                        fireToast(response.data)
                        formReset()
                        getFabric()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
            }catch(e){
                if(e.response.status == 422){
                    var data = [];
                    for(const key in e.response.data.errors){
                        data.push(e.response.data.errors[key][0]);
                    }
                    errors.value = data;
                }
            }
        }

        const editFabric = (fabric) => {
            errors.value = [];
            fabric_id.value = fabric.id;
            form.fabric_name = fabric.fabric_name;
            form.fabric_code = fabric.fabric_code;
            form.status = fabric.status;
        }

        const formReset = () =>{
            fabric_id.value = '';
            form.fabric_name = '';
            form.fabric_code = '';
            form.status = true;
        }
        
        onMounted(getFabric());
        const showPermission = computed(() => window.userPermission)

        return {
            fabrics,
            form,
            fabric_id,
            getFabric,
            editFabric,
            updateFabric,
            formReset,
            storeFabric,
            deleteFabric,
            errors,
            showPermission
        }
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
                            <h4>Fabric</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#fabricModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Fabric Name</th>
                                    <th>Fabric Code</th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(fabric,index) in fabrics.data" :key="fabric.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ fabric.fabric_name }}</td>
                                        <td>{{ fabric.fabric_code }}</td>
                                        <td class="text-center">
                                            <label class="switch s-success  mb-4 mx-5">
                                                <input type="checkbox" :checked="fabric.status == 1 ? true : false" disabled>
                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#fabricModal" @click="editFabric(fabric)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteFabric(fabric.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="fabrics"
                                @pagination-change-page="getFabric"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="fabricModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Fabric</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="Fabric_name">Fabric Name</label>
                                    <input type="text" class="form-control" v-model="form.fabric_name" id="Fabric_name" placeholder="Fabric Name">
                                    <span
                                        v-if="errors.hasOwnProperty('fabric_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.fabric_name[0] }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="fabric_code">Fabric Code</label>
                                    <input type="text" class="form-control" v-model="form.fabric_code" id="fabric_code" placeholder="Fabric Code">
                                    <span
                                        v-if="errors.hasOwnProperty('fabric_code')"
                                        class="text-danger"
                                    >
                                        {{ errors.fabric_code[0] }}
                                    </span>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                <label for="siz-status">Status</label>
                                        <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                            <input v-model="form.status" type="checkbox" :checked="form.status" id="siz-status">
                                            <span class="slider round"></span>
                                        </label>
                                </div>
                            
                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>
            
                                    <button v-if="fabric_id == ''" type="button" class="btn btn-primary" @click="storeFabric">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateFabric">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>