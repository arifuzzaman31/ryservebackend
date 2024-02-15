<script>
import { ref,reactive,computed,onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
      
        const ingredients  = ref([]);
        const errors  = ref([]);
        const ingredient_id  = ref('');
        const form = reactive({
            ingredient_name: '',
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

        const getIngredient = async(page = 1) =>{
            let res = await axios.get(baseUrl+`ingredient/create?page=${page}`);
            ingredients.value = res.data;
            // console.log(ingredients.value)
        }

        const deleteIngredient = async(id)=>{
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
                    axios.delete(baseUrl+`ingredient/${id}`).then(
                        response => {
                            getIngredient()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeIngredient = async() =>{
           try{
                await axios.post('ingredient',form).then(
                    response => {
                        fireToast(response.data)
                        $("#ingredientModal").modal('hide');
                        formReset()
                        getIngredient()
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

        const updateIngredient = async() =>{
           try{
                await axios.patch('ingredient/' + ingredient_id.value,form).then(
                    response => {
                        $("#ingredientModal").modal('hide');
                        fireToast(response.data)
                        getIngredient()
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

        const editIngredient = (season) => {
            ingredient_id.value = season.id;
            form.ingredient_name = season.ingredient_name;
            form.status = season.status;
        }

        const formReset = () =>{
            ingredient_id.value = '';
            errors.value = []
            form.ingredient_name = '';
            form.status = true;
        }
        
        onMounted(getIngredient());
        const showPermission = computed(() => window.userPermission)

        return {
            ingredients,
            form,
            ingredient_id,
            getIngredient,
            editIngredient,
            updateIngredient,
            formReset,
            storeIngredient,
            deleteIngredient,
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
                            <h4>Ingredient</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#ingredientModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Ingredient </th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(ingred,index) in ingredients.data" :key="ingred.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ ingred.ingredient_name }}</td>
                                        <td class="text-center">
                                            <label class="switch s-success  mb-4 mx-5">
                                                <input type="checkbox" :checked="ingred.status == 1 ? true : false" disabled>
                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#ingredientModal" @click="editIngredient(ingred)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteIngredient(ingred.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="ingredients"
                                @pagination-change-page="getIngredient"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="ingredientModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ingredient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="ingredient_name">Ingredient</label>
                                    <input type="text" class="form-control" v-model="form.ingredient_name" id="ingredient_name" placeholder="Ingredient Name">
                                    <span
                                        v-if="errors.hasOwnProperty('ingredient_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.ingredient_name[0] }}
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
            
                                    <button v-if="ingredient_id == ''" type="button" class="btn btn-primary" @click="storeIngredient">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateIngredient">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>