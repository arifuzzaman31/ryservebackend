<script>
import Mixin from '../../mixer'
export default {
    mixins:[Mixin],
    props: ['permissions'],
    data(){
        return {
            form:{
                role_name: '',
                role_permissions: []
            },
            url: baseUrl,
            validation_error: {}
        }
    },
    methods: {
        storePermission(){
            try{
                axios.post('role',this.form).then(
                    response => {
                        this.successMessage(response.data)
                        this.formReset()
                        window.location.href = baseUrl+'role'
                    }
                ).catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors
                    }
                })
            } catch(e){
                this.validationError({'message':'Something went wrong!'})
            }
        },
        updateCategory(){},

        formReset(){
            this.validation_error = {}
            this.form = {
                role_name: '',
                role_permissions: []
            }
        }
    },
}
</script>

<template>
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row" style="width:99%">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between mx-3">
                    <h4>Create Role</h4>
                    <a :href="url+'role'" class="btn btn-success"> Back</a>
                </div>                 
            </div>
        </div>
    </div>
    <form @submit.prevent="storePermission()">
        <div id="tooltips" class="mb-2">
            <div class="statbox widget box ">
                <div class="widget-content ">
                    <div class="row">
                        <div class="col-12">
                            <input type="text" v-model="form.role_name" class="form-control" id="rolename" placeholder="Role Name"/>
                            <span
                                v-if="validation_error.hasOwnProperty('role_name')"
                                class="text-danger"
                            >
                                {{ validation_error.role_name[0] }}
                            </span>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4" v-for="(permission,index) in permissions" :key="index">
                <div id="tooltips" class="mb-2">
                    <div class="statbox widget box ">
                        <div class="widget-content d-flex-column">
                            <h4>{{ index }}</h4>

                            <div class="custom-control custom-checkbox" v-for="item in permission" :key="item.id">
                                <input type="checkbox" class="custom-control-input" :id="item.id" :value="item.id" v-model="form.role_permissions">
                                <label :for="item.id" class="custom-control-label">{{ item.permission_name }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span
                v-if="validation_error.hasOwnProperty('role_permissions')"
                class="text-danger ml-4 mb-4"
            >
                {{ validation_error.role_permissions[0] }}
            </span>
        </div>
        <input type="submit" name="time" class="btn btn-primary" value="Save" />
    </form>
  
</template>


<style scoped>

</style>