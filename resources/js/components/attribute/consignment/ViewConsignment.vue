<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
      
        const consignments  = ref([]);
        const errors  = ref([]);
        const consignment_id  = ref('');
        const form = reactive({
            consignment_name: '',
            status: true
        });
        const toastMixin = Swal.mixin({
            toast: true,
            icon: 'success',
            title: 'General Title',
            animation: false,
            position: 'top-right',
            showConfirmButton: false,
            timer: 1500,
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

        const getConsignment = async(page = 1) =>{
            let res = await axios.get(baseUrl+`consignment/create?page=${page}`);
            consignments.value = res.data;
            // console.log(consignments.value)
        }

        const deleteConsignment = async(id)=>{
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
                    axios.delete(baseUrl+`consignment/${id}`).then(
                        response => {
                            getConsignment()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeConsignment = async() =>{
           try{
                await axios.post('consignment',form).then(
                    response => {
                        fireToast(response.data)
                        $("#consignmentModal").modal('hide');
                        formReset()
                        getConsignment()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
            }catch(e){
                console.log(e.response)
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
            }
        }

        const updateConsignment = async() =>{
           try{
                await axios.patch('consignment/' + consignment_id.value,form).then(
                    response => {
                        $("#consignmentModal").modal('hide');
                        fireToast(response.data)
                        getConsignment()
                        formReset()
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

        const editFit = (season) => {
            consignment_id.value = season.id;
            form.consignment_name = season.consignment_name;
            form.status = season.status;
        }

        const formReset = () =>{
            errors.value = []
            consignment_id.value = '';
            form.consignment_name = '';
            form.status = true;
        }
        
        onMounted(getConsignment());
        const showPermission = computed(() => window.userPermission)

        return {
            consignments,
            form,
            consignment_id,
            getConsignment,
            editFit,
            updateConsignment,
            formReset,
            storeConsignment,
            deleteConsignment,
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
                            <h4>Consignment</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#consignmentModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Consignment </th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(consig,index) in consignments.data" :key="consig.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ consig.consignment_name }}</td>
                                        <td class="text-center">
                                            <label class="switch s-success  mb-4 mx-5">
                                                <input type="checkbox" :checked="consig.status == 1 ? true : false" disabled>
                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#consignmentModal" @click="editFit(consig)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteConsignment(consig.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="consignments"
                                @pagination-change-page="getFit"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="consignmentModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Consignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="consignment_name">Consignment</label>
                                    <input type="text" class="form-control" v-model="form.consignment_name" id="consignment_name" placeholder="Consignment Name">
                                    <span
                                        v-if="errors.hasOwnProperty('consignment_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.consignment_name[0] }}
                                    </span>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                <label for="Fit-status">Status</label>
                                        <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                            <input v-model="form.status" type="checkbox" :checked="form.status" id="siz-status">
                                            <span class="slider round"></span>
                                        </label>
                                </div>
                            
                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>
            
                                    <button v-if="consignment_id == ''" type="button" class="btn btn-primary" @click="storeConsignment">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateConsignment">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>