<script>
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    data(){
        return {
            form:{
                roleName: '',
                rolePermissions: []
            },
            roles: [],
            permissions: [
                {
                    name: "Branch",
                    permissions: [
                        {
                            "id": 1,
                            "permission_name": "Branch View",
                            "slug": "branch-view",
                            "status": 1
                        },
                        {
                            "id": 2,
                            "permission_name": "Branch Edit",
                            "slug": "branch-edit",
                            "status": 1,
                        },
                        {
                            "id": 3,
                            "permission_name": "Branch Delete",
                            "slug": "branch-delete",
                            "status": 1
                        },
                        {
                            "id": 4,
                            "permission_name": "Branch Create",
                            "slug": "attribute-create",
                            "status": 1
                        }
                    ]
                },
                {
                    name: "Listing",
                    permissions: [
                        {
                            "id": 5,
                            "permission_name": "Listing View",
                            "slug": "listing-view",
                            "status": 1
                        },
                        {
                            "id": 6,
                            "permission_name": "Listing Edit",
                            "slug": "listing-edit",
                            "status": 1,
                        },
                        {
                            "id": 7,
                            "permission_name": "Listing Delete",
                            "slug": "listing-delete",
                            "status": 1
                        },
                        {
                            "id": 8,
                            "permission_name": "Listing Create",
                            "slug": "listing-create",
                            "status": 1
                        }
                    ]
                },
                {
                    name: "Listing Type",
                    permissions: [
                        {
                            "id": 9,
                            "permission_name": "Listing Type View",
                            "slug": "listing-type-view",
                            "status": 1
                        },
                        {
                            "id": 10,
                            "permission_name": "Listing Type Edit",
                            "slug": "listing-type-edit",
                            "status": 1,
                        },
                        {
                            "id": 11,
                            "permission_name": "Listing Type Delete",
                            "slug": "listing-type-delete",
                            "status": 1
                        },
                        {
                            "id": 12,
                            "permission_name": "Listing Type Create",
                            "slug": "listing-type-create",
                            "status": 1
                        }
                    ]
                }
            ],
            roles_permission: [],
            url: baseUrl,
            validation_error: {}
        }
    },
    methods: {
        getRole(){

        },
        addRole(){
            $("#createRoleModal").modal('show');
        },
        editRole(role){
            this.form.id = role.id
            this.form.role_name = role.role_name
            this.form.role_permissions = role.role_permission.map(item => item.id)
            $("#updateRoleModal").modal('show');
        },
        formReset(){
            this.validation_error = {}
            this.form = {
                id: '',
                role_name: '',
                role_permissions: []
            }
        },
        deleteRole(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl+`role/${id}`).then(
                        response => {
                            if(response.data.status == 'success'){
                                this.successMessage(response.data)
                                this.formReset()
                                window.location.reload(true)
                            }else{

                                this.validationError(response.data)
                            }
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },
        updatePermission(){
            try{
                axios.put(baseUrl+'role/'+this.form.id,this.form).then(
                    response => {
                        if(response.data.status == 'success'){
                            this.successMessage(response.data)
                            this.formReset()
                            $("#updateRoleModal").modal('hide');
                            window.location.reload(true)
                        }else{

                            this.validationError({'message':'Something went wrong!'})
                        }
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
    },
    computed: {
        // showPermission() {
        //     return window.userPermission;
        // }
    }
}
</script>

<template>
<div class="statbox widget box box-shadow">
    <div class="widget-header">
        <div class="row" style="width:99%">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between mx-3">
                <h4>Role List</h4>
                <button class="btn btn-primary mb-2 mr-3" data-toggle="modal" data-target="#createRoleModal">Add New</button>
            </div>
        </div>
    </div>
    <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox">
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Role Name</th>
                                <th>Permission</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="roles && roles.length > 0">
                            <tr v-for="(role,index) in roles" :key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{ role.roleName }}</td>
                                <td>{{ role.permissions }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning" href="javascript:void(0);" @click="editRole(role)" type="button" title="Edit">Edit</a>
                                    <a class="btn btn-sm mx-1 btn-danger" href="javascript:void(0);" @click="deleteRole(role.id)" type="button" title="Delete">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="4">No Role Found</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
<div id="createRoleModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Create Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content widget-content-area">
                        <form @submit.prevent="storePermission()">
                            <div id="tooltips" class="mb-2">
                                <div class="statbox widget box ">
                                    <div class="widget-content ">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="text" v-model="form.roleName" class="form-control" id="rolename" placeholder="Role Name"/>
                                                <span
                                                    v-if="validation_error.hasOwnProperty('roleName')"
                                                    class="text-danger"
                                                >
                                                    {{ validation_error.roleName[0] }}
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
                                                <h5>{{ permission.name }}</h5>

                                                <div class="custom-control custom-checkbox" v-for="item in permission.permissions" :key="item.id">
                                                    <input type="checkbox" class="custom-control-input" :id="item.id" :value="item.slug" v-model="form.rolePermissions">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="updateRoleModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Update Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content widget-content-area">
                        <form @submit.prevent="updatePermission()">
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
                            <input type="submit" name="time" class="btn btn-primary" value="Update" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
