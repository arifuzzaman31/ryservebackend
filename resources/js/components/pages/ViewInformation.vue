<script>
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import Mixin from '../../mixer';
export default {
    props: ['allcontent'],
    mixins: [Mixin],
    components: {
        QuillEditor
    },
    data(){
        return {
            form : {
                id: '',
                title: '',
                content: '',
                status: 1
            },
            validation_error : {},
            url: baseUrl
        }
    },

    methods: {

        editInfo(item){
            this.formReset()
            this.form.id = item.id
            this.form.title = item.title
            this.form.back_link = item.back_link
            this.form.content = item.content
            this.form.status = item.status
            $("#addInfoModal").modal('show');
        },

        addInformation(){
            axios.post(baseUrl+`add-information`,this.form).then(
                response => {
                    $("#addInfoModal").modal('hide');
                    this.successMessage(response.data)
                    window.location.reload(true)
                }
            ). catch(error => {
                if(error.response.status == 422){
                        this.validation_error = error.response.data.errors;
                    }
            })
        },

        deleteInfo(id){
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
                    axios.delete(baseUrl+`remove-information-data/${id}`).then(
                        response => {
                            this.successMessage(response.data)
                            window.location.reload(true)
                        }
                    ). catch(error => {
                        this.validationError()
                    })
                }
            })

        },


        formReset(){
            this.validation_error = {};
            this.form = {
                id: '',
                title: '',
                content: '',
                status: 1
            }
        }

    },

    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Information</h4>
                            <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('add-info')" data-toggle="modal" data-target="#addInfoModal" @click="formReset">Add New</button>
                        </div>                          
                    </div>
                </div>       
                <div class="widget-content widget-content-area">
                    <div class="table-responsive" style="min-height: 60vh;">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Page Link</th>
                                    <th>status</th>
                                    <th v-if="showPermission.includes('delete-info') && showPermission.includes('edit-info')" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="allcontent.length > 0">
                                <template v-for="(item,index) in allcontent" :key="index">
                                    <tr>
                                        <td>{{ index+1 }}</td>
                                        <td>{{ item.title }}</td>
                                        <td>{{ item.back_link }}</td>
                                       
                                        <td>{{ item.status == 1 ? 'Active' : 'Deactive' }}</td>
                                        <td class="text-center" v-if="showPermission.includes('delete-info') && showPermission.includes('edit-info')">
                                            <button type="button" v-if="showPermission.includes('edit-info')" class="btn btn-sm btn-info" @click="editInfo(item)">Edit</button>
                                            <button type="button" v-if="showPermission.includes('delete-info')" class="btn btn-sm btn-danger ml-2" @click="deleteInfo(item.id)">Delete</button>
                                        </td>
                                    </tr>					
                                </template>
                            </tbody>
                            <tbody v-else class="text-center mt-3">
                                <tr>
                                    <td colspan="5">No Data Found</td>
                                </tr>
                                 
                            </tbody>
                        </table>
                    </div>
                   
                </div>
            </div>
        </div>

        <div id="addInfoModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-3">Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content widget-content-area">
                            <form>
                                <div>
                                    <div class="form-group">
                                        <label for="">Title</label>
                                        <input type="text" class="form-control" v-model="form.title"/>
                                        <span
                                        v-if="validation_error.hasOwnProperty('title')"
                                        class="text-danger"
                                    >
                                        {{ validation_error.title[0] }}
                                    </span>
                                    </div>
                                    <div class="form-group" v-if="form.id !== ''">
                                        <label for="bank_link">Page Link</label>
                                        <input type="text" v-model="form.back_link" class="form-control" id="bank_link" />
                                    </div>
                                    <div class="form-group my-2">
                                        <label for="editor-container">Description</label>
                                        <QuillEditor theme="snow" v-model:content="form.content" contentType="html" />
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" v-model="form.status">
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select>
                            
                                    </div>
                                    <div class="modal-footer md-button">
                                        <button class="btn btn-default" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i>Discard</button>
                                        <button type="button" @click.prevent="addInformation()" class="btn btn-primary">{{ form.id == '' ? 'Add' : 'Update' }}</button>
                                    </div>
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
.ql-container {
  height: 15rem !important;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.ql-editor {
  height: 15rem !important;
  flex: 1;
  overflow-y: auto;
  width: 100%;
}
</style>