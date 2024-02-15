<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
        const colours  = ref([]);
        const errors  = ref([]);
        const color_id  = ref('');
        const form = reactive({
            color_name: '',
            color_code:"",           
            status: true
        });
        const keyword = reactive({
            key:'',
            url : baseUrl
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

        const getColour = async(page = 1) =>{
            let res = await axios.get(baseUrl+`colour/create?&keyword=${keyword.key}&per_page=10&page=${page}`);
            colours.value = res.data;
            // console.log(colours.value)
        }

        const onPress = () => {
            if (keyword.key.length < 3) {
                return;
            }
            return getColour()
            
        }

        const deleteColor = async(id)=>{
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
                    axios.delete(baseUrl+`colour/${id}`).then(
                        response => {
                            getColour()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeColor = async() =>{
           try{
                await axios.post('colour',form).then(
                    response => {
                        fireToast(response.data)
                        $("#ColorModal").modal('hide');
                        formReset()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
                
                getColour()
            }catch(e){
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
            }
        }

        const updateColor = async() =>{
           try{
                await axios.patch('colour/' + color_id.value,form).then(
                    response => {
                        $("#ColorModal").modal('hide');
                        fireToast(response.data)
                        formReset()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
            getColour()
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

        const editColour = (color) => {
            color_id.value = color.id;
            form.color_name = color.color_name;
            form.color_code = color.color_code;
            form.status = color.status;
        }

        const clearfilter = () => {
            keyword.key = ''
            getColour()
        }

        const formReset = () =>{
            color_id.value = '';
            errors.value = [];
            form.color_name = '';
            form.color_code = '';
            form.status = true;
        }
        
        onMounted(getColour());
        
        const showPermission = computed(() => window.userPermission)

        return {
            colours,
            form,
            color_id,
            getColour,
            editColour,
            updateColor,
            formReset,
            storeColor,
            deleteColor,
            errors,
            keyword,
            onPress,
            clearfilter,
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
                            <h4>Colour</h4>
                            <button class="btn btn-primary mb-2" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#ColorModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="d-flex flex-row col-4 mb-2">
                        <input id="search" placeholder="Search By Name" type="text" class="form-control form-control-sm"  @keyup.prevent="onPress" v-model="keyword.key" />
                        <button type="button" class="btn btn-danger btn-sm mx-3" @click="clearfilter">CLEAR</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Colour Code</th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(color,index) in colours.data" :key="color.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ color.color_name }}</td>
                                        <td>{{ color.color_code }}</td>
                                        <td>{{ color.status == 1 ? 'active' : 'Inactive' }}</td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#ColorModal" @click="editColour(color)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteColor(color.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="colours"
                                @pagination-change-page="getColour"
                            />
                    </div>
                </div>
            </div>
        </div>
        <div id="ColorModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Colour</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="color_name">Colour Name</label>
                                    <input type="text" class="form-control" v-model="form.color_name" id="color_name" placeholder="Colour Name">
                                    <span
                                        v-if="errors.hasOwnProperty('color_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.color_name[0] }}
                                    </span>
                                </div>

                                <div class="form-group">
                                <label for="color_code">Colour Code</label>
                                <input type="color" class="form-control" v-model="form.color_code" id="color_code" placeholder="Colour Code">
                                <span
                                    v-if="errors.hasOwnProperty('color_code')"
                                    class="text-danger"
                                >
                                    {{ errors.color_code[0] }}
                                </span>
                            </div>

                                <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                <label for="cat-status">Status</label>
                                        <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                            <input v-model="form.status" type="checkbox" :checked="form.status" id="cat-status">
                                            <span class="slider round"></span>
                                        </label>
                                </div>
                            
                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
            
                                    <button v-if="color_id == ''" type="button" class="btn btn-primary" @click="storeColor">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateColor">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>