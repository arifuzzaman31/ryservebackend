<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import Mixin from '../../mixer'
export default {
    mixins:[Mixin],
    components:{
        Bootstrap4Pagination
    },

    data(){
        return {
            page_sections: [],
            sect_id: '',
            form: {
                section_name: '',
                status: false
            },
            url: baseUrl,
            limit: 3,
            keepLength: false,
            validation_error: {},
        }
    },
    methods:{
        getPageData(page = 1){
            try{
                axios.get(baseUrl+`get-page-section?page=${page}`)
                .then(response => {
                    this.page_sections = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },

        editSect(camp){
            this.sect_id = camp.id
            this.form.section_name = camp.section_name
            this.form.status = camp.status == 1 ? true : false
            $("#updateSectionModal").modal('show');
        },

        deletePageSection(id){
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
                  axios.delete(baseUrl+`page-section/${id}`).then(
                      response => {
                          this.successMessage(response.data)
                        this.getPageData()
                        //   console.log(response.data)
                      }
                  ). catch(error => {
                    console.log(error)
                  })
              }
          })

        },

        updateSection(){
         try{
               axios.patch('page-section/' + this.sect_id,this.form).then(
                  response => {
                      $("#updateSectionModal").modal('hide');
                      this.successMessage(response.data)
                      this.formReset()
                      this.getPageData()
                  }
              ). catch(e => {
                 if(e.response.status == 422){
                    this.validation_error = e.response.data.errors;
                    this.validationError();
                  }
              })
          }catch(e){
              if(e.response.status == 422){

              }
          }
      },
      formReset(){
            this.sect_id = ''
            this.form = {
                section_name: '',
                status: false
            }
            this.validation_error = {}
        },

    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    },
    mounted(){
        this.getPageData()
    }
}
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Section Name</th>
                            <th>Pattern</th>
                            <th>Use For</th>
                            <th>Precedense</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(section,index) in page_sections.data" :key="section.id">
                            <tr>
                                <td>{{ index+1 }}</td>
                                <td>{{ section.section_name }}</td>
                                <td>{{ section.pattern }}</td>
                                <td>{{ section.use_for }}</td>
                                <td>{{ section.precedence }}</td>
                                <td class="text-center">{{ section.status == 1 ? 'Active' : 'Deactive' }}</td>
                                <td>
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" @click="editSect(section)" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <li><a :href="url+'section-product/'+section.id" data-toggle="tooltip" data-placement="top" title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                        </a></li>
                                    <li><a href="javascript:void(0);" @click="deletePageSection(section.id)" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                </ul>
                            </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                    <Bootstrap4Pagination
                        :data="page_sections"
                        :limit="limit"
                        :keep-length="keepLength"
                        @pagination-change-page="getPageData"
                    />
                    </div>

                </div>
            </div>
        </div>
        <div id="updateSectionModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Update Section</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content widget-content-area">
                            <form @submit.prevent="updateSection()">
                                <div class="form-group">
                                    <label for="section_name">Campaign Name</label>
                                    <input type="text" v-model="form.section_name" id="section_name" class="form-control" placeholder="Section name">
                                    <span
                                        v-if="validation_error.hasOwnProperty('section_name')"
                                        class="text-danger"
                                    >
                                        {{ validation_error.section_name[0] }}
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

                                    <button type="submit" class="btn btn-primary">Submit</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
