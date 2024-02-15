<script>
import Mixin from "../../mixin";
import { EventBus } from "../../vue-assets";
export default {
    mixins: [Mixin],
    data(){
        return {
            form: {
                color_name: "",
                color_code:"",
                status: true
            },
            isLoading: false,
            validation_error : {},
            buttonName: 'Save',
            url : baseUrl
        }
    },
    setup: () => ({

    }),

    methods: {
        saveData() {
            this.isLoading = true
            this.buttonName = 'Loading...'
            axios.post(baseUrl+'colour', this.form).then(
                response => {
                    this.isLoading = false;
                    $("#ColorAddModal").modal("hide");
                    this.successMessage(response.data);
                    this.clearForm();
                    EventBus.$emit("colour-created");
                }
            ). catch(error => {
                 if (error.response.status == 422) {
                    this.validation_error = error.response.data.errors;
                    console.log(this.validation_error);
                    this.validationError();
                } else {
                    this.successMessage(error);
                }
            })
        },

        clearForm(){
            this.form = {
                color_name: "",
                status: true
            }
            this.isLoading = false
            this.buttonName = 'Save'
            this.validation_error = {}
        }
    }
}
</script>

<template>
    <div id="ColorAddModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Colour</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="widget-content widget-content-area">
                        <form @submit.prevent="saveData()">
                            <div class="form-group">
                                <label for="color_name">Colour Name</label>
                                <input type="text" class="form-control" v-model="form.color_name" id="color_name" placeholder="Colour Name">
                                <span
                                    v-if="validation_error.hasOwnProperty('color_name')"
                                    class="text-danger"
                                >
                                    {{ validation_error.color_name[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="color_code">Colour Code</label>
                                <input type="color" class="form-control" v-model="form.color_code" id="color_code" placeholder="Colour Code">
                                <span
                                    v-if="validation_error.hasOwnProperty('color_code')"
                                    class="text-danger"
                                >
                                    {{ validation_error.color_code[0] }}
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
                                <button class="btn btn-secondary mb-3 mr-3">
                                    <span v-if="isLoading" class="spinner-grow text-danger align-self-center"></span>
                                {{buttonName}}</button>
                            </div>
                        </form>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</template>