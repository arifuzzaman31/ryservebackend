<script>
import { ref,reactive,computed, onMounted } from 'vue';
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    components:{
        Bootstrap4Pagination
    },
    setup(){
      
        const artist  = ref([]);
        const errors  = ref([]);
        const artist_id  = ref('');
        const form = reactive({
            artist_name: '',
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

        const getArtist = async(page = 1) =>{
            let res = await axios.get(baseUrl+`artist/create?&keyword=${keyword.key}&per_page=10&page=${page}`);
            artist.value = res.data;
            // console.log(artist.value)
        }

        const onPress = () => {
            if (keyword.key.length < 3) {
                return;
            }
            return getArtist()
            
        }

        const deleteArtist = async(id)=>{
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
                    axios.delete(baseUrl+`artist/${id}`).then(
                        response => {
                            getArtist()
                            fireToast(response.data)
                        }
                    ). catch(error => {
                    
                    })
                }
            })

        }

        const storeArtist = async() =>{
           try{
                await axios.post('artist',form).then(
                    response => {
                        fireToast(response.data)
                        $("#artistModal").modal('hide');
                        formReset()
                        getArtist()
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

        const updateArtist = async() =>{
           try{
                await axios.patch('artist/' + artist_id.value,form).then(
                    response => {
                        $("#artistModal").modal('hide');
                        fireToast(response.data)
                        getArtist()
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

        const editArtist = (artist) => {
            artist_id.value = artist.id;
            form.artist_name = artist.artist_name;
            form.status = artist.status;
        }

        const formReset = () =>{
            errors.value = []
            artist_id.value = '';
            form.artist_name = '';
            form.status = true;
        }
        
        onMounted(getArtist());
        const showPermission = computed(() => window.userPermission)

        return {
            artist,
            form,
            artist_id,
            getArtist,
            editArtist,
            updateArtist,
            formReset,
            storeArtist,
            deleteArtist,
            errors,
            keyword,
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
                            <h4>Artist</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('attribute-create')" data-toggle="modal" data-target="#artistModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="row col-4 mb-2">
                        <input id="search" placeholder="Search By Name" type="text" class="form-control form-control-sm"  @keyup.prevent="onPress" v-model="keyword.key" />
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Artist Name</th>
                                    <th class="text-center">Status</th>
                                    <th v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(art,index) in artist.data" :key="art.id">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ art.artist_name }}</td>
                                        <td class="text-center">
                                            <label class="switch s-success  mb-4 mx-5">
                                                <input type="checkbox" :checked="art.status == 1 ? true : false" disabled>
                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        <td v-if="showPermission.includes('attribute-edit') || showPermission.includes('attribute-delete')">
                                            <button type="button" v-if="showPermission.includes('attribute-edit')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#artistModal" @click="editArtist(art)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('attribute-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteArtist(art.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                        </table>
                            <Bootstrap4Pagination
                                :data="artist"
                                @pagination-change-page="getArtist"
                            />
                    </div>

                </div>
            </div>
        </div>
        <div id="artistModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Artist</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="widget-content widget-content-area">
                            <form>
                                <div class="form-group">
                                    <label for="artist_name">Artist Name</label>
                                    <input type="text" class="form-control" v-model="form.artist_name" id="artist_name" placeholder="Artist Name">
                                    <span
                                        v-if="errors.hasOwnProperty('artist_name')"
                                        class="text-danger"
                                    >
                                        {{ errors.artist_name[0] }}
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
            
                                    <button v-if="artist_id == ''" type="button" class="btn btn-primary" @click="storeArtist">Submit</button>

                                    <button v-else type="button" class="btn btn-primary" @click="updateArtist">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>