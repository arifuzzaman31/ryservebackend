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
            businesses: [],
            url: baseUrl,
            validation_error: {},
        }
    },
    methods:{
        getBusiness(){
            try{
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                axios.get(`${apiUrl}backendapi/business`,{
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                })
                .then(response => {
                    this.businesses = response.data
                    console.log(response.data)
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },

        deleteCampaign(id){
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
                  axios.delete(apiUrl+`business?id=${id}`).then(
                      response => {
                          this.successMessage(response.data)
                        // this.getCampaign()
                        //   console.log(response.data)
                      }
                  ). catch(error => {
                    console.log(error)
                  })
              }
          })

        },

        storeCampaign(){
            try{
                axios.post('campaign',this.form).then(
                    response => {
                        this.successMessage(response.data)
                        $("#createCampModal").modal('hide');
                        this.getCampaign()
                    }
                ). catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors;
                        this.validationError();
                    }
                })

            }catch(e){
                if(e.response.status == 422){
                    errors.value = e.response.data.errors;
                }
                this.validationError();
            }
        },

        formReset(){
            this.camp_id = ''
            this.form = {
                campaign_name: '',
                campaign_title: '',
                campaign_banner: '',
                campaign_meta_image: '',
                start_at: '',
                expire_at: '',
                status: true
            }
            this.validation_error = {}
        },

        editCamp(camp){
            this.camp_id = camp.id
            this.form.campaign_name = camp.campaign_name
            this.form.campaign_title = camp.campaign_title
            this.form.campaign_banner = camp.campaign_banner_default
            this.form.campaign_meta_image = camp.campaign_meta_image
            this.form.start_at = camp.campaign_start_date
            this.form.expire_at = camp.campaign_expire_date
            this.form.status = camp.status = 1 ? true : false
            $("#updateCampModal").modal('show');
        },

        updateCampaign(){
         try{
               axios.patch('campaign/' + this.camp_id,this.form).then(
                  response => {
                      $("#updateCampModal").modal('hide');
                      this.successMessage(response.data)
                      this.formReset()
                      this.getCampaign()
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

    },
    computed: {
    },
    mounted(){
        this.getBusiness()
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
                            <h4>Business</h4>
                <a :href="url+'create/business'"
                    class="btn btn-primary mb-2 mr-3"
                >
                    Create Business
        </a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Business Name</th>
                            <th>Business Type</th>
                            <th>Service Type</th>
                            <th>Business Category</th>
                            <th>Area</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(business,index) in businesses" :key="business.id">
                            <tr>
                                <td>{{ index+1 }}</td>
                                <td>{{ business.businessName }}</td>
                                <td>{{ business.businessType }}</td>
                                <td>{{ business.serviceType }}</td>
                                <td>{{ business.businessCategory }}</td>
                                <td>{{ business.city }}</td>
                                <td class="text-center">
                                    <label class="switch s-success  mb-4 mx-5">
                                        <input type="checkbox" :checked="business.status == 1 ? true : false" disabled>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                        </a></li>
                                    <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                    <!-- <li><a :href="'asset/'+business.id" title="Asset">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </a></li> -->
                                </ul>
                            </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>


<style scoped>

</style>
