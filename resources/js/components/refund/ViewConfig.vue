<script>
import axios from 'axios';
import Mixin from '../../mixer';

export default {
    name: 'refund',
    props: ['configinfo'],
    mixins: [Mixin],

    data(){
        return {
            form : {
                id:'',
                refund_within_days: '',
                status: 1
            },
            url: baseUrl
        }
    },

    methods: {
        updateConfig(){
            axios.post(baseUrl+`refund/settings/update`,this.form)
            .then(result => {
                this.successMessage(result.data)
            })
            .catch(errors => {
                console.log(errors);
            });  
        },
    },
    mounted(){
        this.form.id = this.configinfo.id
        this.form.refund_within_days = this.configinfo.refund_within_days
        this.form.status = this.configinfo.status
    }
}
</script>
<template>
  <div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card t-page-header">
                <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                    <div class="tt-page-title">
                        <h2 class="h5 mb-lg-0">Refund Configuration</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4 g-4">
        <!--left sidebar-->
        <div class="col-xl-9 col-2 col-md-2 col-lg-2 col-xl-1">
          
            <form class="pb-650">
                <div class="card mb-4" id="section-1">
                    <div class="card-body">
                        <h5 class="mb-3">Basic Information</h5>
                        <div class="mb-3">
                            <label for="refund_within_days" class="form-label">Allow Refund/Cancellation Within Days</label>
                            <input class="form-control" type="hidden" name="id" v-model="form.id" />
                            <input class="form-control" type="number" min="0" v-model="form.refund_within_days" placeholder="Type refund days" required="">
                        </div>

                        <div class="mb-1">
                            <label for="enable_refund_system" class="form-label">Enable Refund System</label>
                            <select class="form-control" v-model="form.status">
                                <option value="1" selected>Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--basic information end-->

                <!-- submit button -->
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit"  @click.prevent="updateConfig()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save me-1"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Save Configurations
                            </button>
                        </div>
                    </div>
                </div>
                <!-- submit button end -->
            </form>
        </div>

        <!--right sidebar-->
        <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
            <div class="card tt-sticky-sidebar d-none d-xl-block">
                <div class="card-body">
                    <h5 class="mb-3">Refund Information</h5>
                    <div class="tt-vertical-step">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#section-1" class="active">Refund Information</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style>

</style>

