<script>
import Mixin from '../../mixer'

export default {
    mixins:[Mixin],
    data(){
        return {
            form:{
                assetId: '',
                roleName: '',
                rolePermissions: [],
                status: 'true'
            },
            roles: [],
            assetes: [],
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
                            "slug": "branch-create",
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
                },
                {
                    name: "Reservation",
                    permissions: [
                        {
                            "id": 13,
                            "permission_name": "View Reservation",
                            "slug": "reservation-view",
                            "status": 1
                        },
                        {
                            "id": 14,
                            "permission_name": "Add Reservation",
                            "slug": "add-reservation",
                            "status": 1,
                        },
                        {
                            "id": 15,
                            "permission_name": "Edit Reservation",
                            "slug": "edit-reservation",
                            "status": 1
                        },
                        {
                            "id": 16,
                            "permission_name": "Delete Reservation",
                            "slug": "delete-reservation",
                            "status": 1
                        }
                    ]
                }
            ],
            url: baseUrl,
            isLoading : false,
            isSubmiting : false,
            validation_error: {}
        }
    },
    methods: {
        async getRole(){
            try {
                this.isLoading = true
                const token = await this.getUserToken();
                await axios
                    .get(`${apiUrl}backendapi/roles?from=1&to=15`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.roles = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                    this.isLoading = false
            } catch (e) {
                console.log(e);
            }
        },
        addRole(){
            $("#createRoleModal").modal('show');
        },
        async getAsset(){
            try {
                const token = await this.getUserToken();
                await axios
                    .get(`${apiUrl}backendapi/asset`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.assetes = response.data;
                        // console.log(response.data)
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } catch (e) {
                console.log(e);
            }
        },
        async editRole(role){
            this.form.id = role.id
            this.form.assetId = role.assetId
            this.form.roleName = role.roleName
            this.form.rolePermissions = role.permissions
            // console.log(role)
            // this.form.role_permissions = role.role_permission.map(item => item.id)
            $("#updateRoleModal").modal('show');
        },
        formReset(){
            this.validation_error = {}
            this.form = {
                id: '',
                assetId: '',
                roleName: '',
                rolePermissions: [],
                status:'true'
            }
        },
        async deleteRole(id){
            const token = await this.getUserToken()
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then(async (result) => {
                if (result.isConfirmed) {
                   await axios.delete(`${apiUrl}backendapi/roles?id=${id}`,{
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                   }).then(
                        response => {
                            if (response.status == 200) {
                                this.successMessage({ status: 'success', message: 'Role Deleted Successful' })
                                this.formReset()
                                this.getRole()
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
        async storeRolePermission(){
            try{
                const token = await this.getUserToken();
                axios.post(`${apiUrl}backendapi/roles`,this.form, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }).then(
                    response => {
                        if(response.status == 201){
                        this.successMessage({status:'success',message:'New Role Created Successful'})
                        $("#createRoleModal").modal('hide');
                        this.formReset()
                        this.getRole()
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
        async updatePermission(){
            try {
                this.isSubmiting = true
                const token = await this.getUserToken()
                    await axios.put(`${apiUrl}backendapi/roles?id=${this.form.id}`,this.form, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    }).then(
                    response => {
                        // console.log(response.data)
                        if (response.status == 200) {
                            this.successMessage({ status: 'success', message: 'Role Updated Successful' })
                            $("#updateRoleModal").modal('hide');
                            this.getRole()
                        }
                        this.formReset()
                    }
                ).catch(e => {
                    // console.log(e.response.data)
                    if (e.response.status == 400) {
                        this.validation_error = e.response.data;
                        this.validationError(e.response.data);
                    }
                })
                this.isSubmiting = false
            } catch (e) {
                console.log(e.response)
            }
        },
    },
    mounted(){
        this.getAsset()
        this.getRole()
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
                <button class="btn btn-primary mb-2 mr-3" data-toggle="modal" data-target="#createRoleModal" @click="formReset()">Add New</button>
            </div>
        </div>
    </div>
    <div class="widget-content widget-content-area text-center" v-if="isLoading">
        <div class="spinner-border text-success align-self-center loader-xl"></div>
    </div>
    <div id="tableHover" class="col-lg-12 col-12 layout-spacing" v-else>
        <div class="statbox">
            <div class="widget-content widget-content-area">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Branch Name</th>
                                <th>Role Name</th>
                                <th style="width: 50%;">Permission</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="roles && roles.length > 0">
                            <tr v-for="(role,index) in roles" :key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{ role.asset.propertyName }}</td>
                                <td>{{ role.roleName }}</td>
                                <td style="width: 50%;">{{ role.permissions.join(', ') }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-warning" href="javascript:void(0);" @click="editRole(role)" type="button" title="Edit">Edit</a>
                                    <a class="btn btn-sm mx-1 btn-danger" href="javascript:void(0);" @click="deleteRole(role.id)" type="button" title="Delete">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr class="text-center text-bold">
                                <td colspan="5">No Role Found</td>
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
                        <form @submit.prevent="storeRolePermission()">
                            <div id="tooltips" class="mb-2">
                                <div class="statbox widget box ">
                                    <div class="widget-content ">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <select id="assetId" class="form-control" v-model="form.assetId">
                                                    <option value="">Choose Asset...</option>
                                                    <option v-for="(asset,ind) in assetes" :key="ind" :value="asset.id">{{asset.propertyName}}</option>
                                                </select>
                                                <div
                                                    v-if="validation_error.hasOwnProperty('assetId')"
                                                    class="text-danger font-weight-bold"
                                                >
                                                    {{ validation_error.assetId[0] }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
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
                                            <div class="col-md-6 col-12">
                                                <select id="assetId" class="form-control" v-model="form.assetId">
                                                    <option value="">Choose Asset...</option>
                                                    <option v-for="(asset,ind) in assetes" :key="ind" :value="asset.id">{{asset.propertyName}}</option>
                                                </select>
                                                <div
                                                    v-if="validation_error.hasOwnProperty('assetId')"
                                                    class="text-danger font-weight-bold"
                                                >
                                                    {{ validation_error.assetId[0] }}
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
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
                                    v-if="validation_error.hasOwnProperty('rolePermissions')"
                                    class="text-danger ml-4 mb-4"
                                >
                                    {{ validation_error.rolePermissions[0] }}
                                </span>
                            </div>
                            <button class="btn btn-success mt-1 btn-lg" type="submit">
                                <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>
                                Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.loader-xl {
    width: 5rem;
    height: 5rem;
  }
</style>
