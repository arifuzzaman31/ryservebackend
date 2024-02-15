<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
      
        const designers  = ref([]);
        const errors  = ref([]);
        const designer_id  = ref('');
        const form = reactive({
            designer_name: '',
            designer_sort_name: '',
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

        const getDesigner = async(page = 1) =>{
            let res = await axios.get(baseUrl+`designers/create?page=${page}`);
            designers.value = res.data;
            // console.log(designers.value)
        }

        const deleteDesigner = async(id)=>{
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
                    axios.delete(baseUrl+`designers/${id}`).then(
                        response => {
                            getDesigner()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeDesigner = async() =>{
           try{
                await axios.post('designers',form).then(
                    response => {
                        fireToast(response.data)
                        $("#designerModal").modal('hide');
                        formReset()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
                getDesigner()
            }catch(e){
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
            }
        }

        const updateDesigner = async() =>{
           try{
                await axios.patch('designers/' + designer_id.value,form).then(
                    response => {
                        $("#designerModal").modal('hide');
                        fireToast(response.data)
                        formReset()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
                getDesigner()
                
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

        const editDesigner = (vendor) => {
            designer_id.value = vendor.id;
            form.designer_name = vendor.designer_name;
            form.designer_sort_name = vendor.designer_sort_name;
            form.status = vendor.status;
        }

        const formReset = () =>{
            designer_id.value = '';
            errors.value = [];
            form.designer_name = '';
            form.designer_sort_name = '';
            form.status = true;
        }
        
        onMounted(getDesigner());
        const showPermission = computed(() => window.userPermission)

        return {
            designers,
            form,
            designer_id,
            getDesigner,
            editDesigner,
            updateDesigner,
            formReset,
            storeDesigner,
            deleteDesigner,
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
                            <h4>Designer</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#designerModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Designer Name</th>
                                    <th>Short Name</th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(designer,index) in designers.data" :key="designer.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ designer.designer_name }}</td>
                                        <td>{{ designer.designer_sort_name }}</td>
                                        <td class="text-center">
                                            <label class="switch s-success  mb-4 mx-5">
                                                <input type="checkbox" :checked="designer.status == 1 ? true : false" disabled>
                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#designerModal" @click="editDesigner(designer)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteDesigner(designer.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="designers"
                                @pagination-change-page="getDesigner"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="designerModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Designer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="designer_name">Designer Name</label>
                                    <input type="text" class="form-control" v-model="form.designer_name" id="designer_name" placeholder="Designer Name">
                                    <span
                                        v-if="errors.hasOwnProperty('designer_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.designer_name[0] }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="designer_name">Short Name</label>
                                    <input type="text" class="form-control" v-model="form.designer_sort_name" id="designer_sort_name" placeholder="Short Name">
                                    <span
                                        v-if="errors.hasOwnProperty('designer_sort_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.designer_sort_name[0] }}
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
            
                                    <button v-if="designer_id == ''" type="button" class="btn btn-primary" @click="storeDesigner">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateDesigner">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>