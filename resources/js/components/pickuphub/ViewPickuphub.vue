<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){

        const pickuphub  = ref([]);
        const errors  = ref([]);
        const pickup_id  = ref('');
        const form = reactive({
            hub_name: '',
            hub_code: '',
            type: 'warehouse',
            hub_address: '',
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

        const getPickupHub = async(page = 1) =>{
            let res = await axios.get(baseUrl+`pickuphub/create?page=${page}`);
            pickuphub.value = res.data;
            // console.log(pickuphub.value)
        }

        const deleteHub = async(id)=>{
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
                    axios.delete(baseUrl+`pickuphub/${id}`).then(
                        response => {
                            getPickupHub()
                            fireToast(response.data)
                        }
                    ). catch(error => {

                    })
                }
            })

        }

        const storePickupHub = async() =>{
           try{
                await axios.post('pickuphub',form).then(
                    response => {
                        fireToast(response.data)
                        $("#PickuphubModal").modal('hide');
                        formReset()
                        getPickupHub()
                    }
                ). catch(e => {
                   if(e.response.status == 422){
                        errors.value = e.response.data.errors;
                    }
                })
            }catch(e){
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
            }
        }

        const updatePickupHub = async() =>{
           try{
                await axios.patch('pickuphub/' + pickup_id.value,form).then(
                    response => {
                        $("#PickuphubModal").modal('hide');
                        fireToast(response.data)
                        getPickupHub()
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

        const editPickupHub = (pickup) => {
            pickup_id.value = pickup.id;
            form.hub_name = pickup.hub_name;
            form.hub_code = pickup.hub_code;
            form.type = pickup.type;
            form.hub_address = pickup.hub_address;
            form.status = pickup.status;
        }

        const formReset = () =>{
            errors.value = []
            pickup_id.value = '';
            form.hub_name = '';
            form.hub_code = '';
            form.type = 'warehouse';
            form.hub_address = '';
            form.status = true;
        }

        onMounted(getPickupHub());
        const showPermission = computed(() => window.userPermission)

        return {
            pickuphub,
            form,
            pickup_id,
            getPickupHub,
            editPickupHub,
            updatePickupHub,
            formReset,
            storePickupHub,
            deleteHub,
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
                            <h4>Pickup Point</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#PickuphubModal" @click="formReset">Add New</button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Hub Name</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Address</th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(pickup,index) in pickuphub.data" :key="pickup.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ pickup.hub_name }}</td>
                                        <td>{{ pickup.hub_code }}</td>
                                        <td>{{ pickup.type }}</td>
                                        <td>{{ pickup.hub_address }}</td>
                                        <td class="text-center">
                                            {{ pickup.status == 1 ? 'Active' : 'Deactive' }}
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#PickuphubModal" @click="editPickupHub(pickup)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteHub(pickup.id)">Delete</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="pickuphub"
                                @pagination-change-page="getPickupHub"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="PickuphubModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pickup Point</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="hub_name">Hub Name</label>
                                    <input type="text" class="form-control" v-model="form.hub_name" id="hub_name" placeholder="Hub Name">
                                    <span
                                        v-if="errors.hasOwnProperty('hub_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.hub_name[0] }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="hub_code">Hub Code</label>
                                    <input type="text" class="form-control" v-model="form.hub_code" id="hub_code" placeholder="Hub Name">
                                    <span
                                        v-if="errors.hasOwnProperty('hub_code')"
                                        class="text-danger"
                                    >
                                        {{ errors.hub_code[0] }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="type_hub">Hub Type</label>
                                    <select class="form-control" v-model="form.type">
                                        <option value="store">Store</option>
                                        <option value="warehouse">Warehouse</option>
                                    </select>
                                    <span
                                        v-if="errors.hasOwnProperty('type')"
                                        class="text-danger"
                                    >
                                        {{ errors.type[0] }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="hub_address">Hub Address</label>
                                    <input type="text" class="form-control" v-model="form.hub_address" id="hub_address" placeholder="Hub Address">
                                    <span
                                        v-if="errors.hasOwnProperty('hub_address')"
                                        class="text-danger"
                                    >
                                        {{ errors.hub_address[0] }}
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

                                    <button v-if="pickup_id == ''" type="button" class="btn btn-primary" @click="storePickupHub">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updatePickupHub">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
