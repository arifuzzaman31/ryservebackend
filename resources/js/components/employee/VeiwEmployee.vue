<script>
import Mixin from '../../mixer'

export default {
    mixins: [Mixin],
    components:{
    },
    data(){
        return {
            employee: {
                name: '',
                email: '',
                userType: '',
                phoneNumber: '',
                occupation: '',
                designation: '',
                password: '',
                roleId: '',
                status: 'true'
            },
            employees: [],
            roles: [],
            keyword: '',
            employee_id: '',
            isLoading:false,
            isSubmiting:false,
            url: baseUrl,
            validation_error : {}
        }
    },

    methods: {
        async getRole(){
            try {
                this.isLoading = true
                const token = await this.getUserToken();
                await axios
                    .get(`${apiUrl}backendapi/roles?no_paginate=yes`, {
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
        async getEmployee(){
            try {
                this.isLoading = true
                const token = await this.getUserToken();
                await axios
                    .get(`${apiUrl}backendapi/employee?from=1&to=15`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.employees = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                    this.isLoading = false
            } catch (e) {
                console.log(e);
            }
        },

        async storeEmp(){
            try{
                this.isSubmiting = true
                const token = await this.getUserToken()
                await axios.post(`${apiUrl}backendapi/employee`,this.employee, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        // console.log(response.data)
                        if (response.status == 201) {
                            this.successMessage({ status: 'success', message: 'Employee Created Successful' })
                            $("#emplModal").modal('hide');
                            this.formReset()
                            this.getEmployee()
                        }
                        // this.formReset()
                        this.isSubmiting = false
                    }
                ). catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors
                    } else {
                        // this.validationError()
                        console.log(e.response)
                    }
                    this.isSubmiting = false
                })
            }catch(e){
                console.log(e.response)
            }
        },

        editEmp(emp){
            this.employee_id = emp.id
            this.employee.name = emp.name
            this.employee.email = emp.email
            this.employee.userType = emp.userType
            this.employee.phoneNumber = emp.phoneNumber
            this.employee.occupation = emp.occupation
            this.employee.designation = emp.designation
            this.employee.roleId = emp.roleId
            $("#emplModal").modal('show');
        },

        async deleteEmp(id){
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
                    await axios.delete(`${apiUrl}backendapi/employee?id=${id}`,{
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    }).then(
                        response => {
                            if(response.status == 200){
                                this.successMessage({status: 'success', message: 'Employee Deleted Successful'})
                                $("#emplModal").modal('hide');
                                this.getEmployee()
                            }
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        async updateEmp(){
            try{
                this.isSubmiting = true
                const token = await this.getUserToken()
                await axios.put(`${apiUrl}backendapi/employee?id=${this.employee_id}`,this.employee, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        if (response.status == 200) {
                            this.successMessage({ status: 'success', message: 'Employee Updated Successful' })
                            $("#emplModal").modal('hide');
                            this.formReset()
                            this.getEmployee()
                        }
                        // this.formReset()
                        this.isSubmiting = false
                    }
                ). catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors
                    } else {
                        this.validationError()
                    }
                    this.isSubmiting = false
                })
            }catch(e){
                console.log(e.response)
            }
        },
        searchKeyword(){
            if(this.keyword.length < 3) return false;
            this.getEmployee()
        },

        formReset(){
            this.employee = {
                name: '',
                email: '',
                userType: '',
                phoneNumber: '',
                occupation: '',
                designation: '',
                roleId: '',
                status: 'true'
            }
            this.keyword = ''
            this.employee_id = ''
            this.isLoading =false,
            this.isSubmiting =false,
            this.validation_error = {}
        },
    },

    mounted(){
        this.getRole()
        this.getEmployee()
    },
    computed: {
        // showPermission() {
        //     return window.userPermission;
        // }
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
                        <h4>Employee</h4>
                        <button class="btn btn-primary mb-2 mr-3" data-toggle="modal" data-target="#emplModal" @click="formReset">Add New</button>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area text-center" v-if="isLoading">
                <div class="spinner-border text-success align-self-center loader-xl"></div>
            </div>
            <div class="widget-content widget-content-area" v-else>
               <!-- <h3> Under Development</h3> -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Name</th>
                                <th>Branch Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>User Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="employees && employees.length > 0">
                            <template v-for="(empl,index) in employees" :key="index">
                                <tr>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ empl.name }}</td>
                                    <td>{{ empl.roles.asset.propertyName }}</td>
                                    <td>{{ empl.email }} </td>
                                    <td>{{ empl.roleId ? empl.roles.roleName : 'No Role'}} </td>
                                    <td>{{ strippedContent(empl.userType) }} </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" @click="editEmp(empl)">Edit</button>
                                        <button type="button" class="btn btn-sm btn-danger ml-2" @click="deleteEmp(empl.id)">Delete</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody v-else>
                            <tr class="text-center text-bold">
                                <td colspan="6">No Employee Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div id="emplModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content widget-content-area">
                        <form>
                            <div class="form-row">
                                <div class="col-6">
                                    <label for="emp_name">Name</label>
                                    <input type="text" class="form-control" v-model="employee.name" id="emp_name" placeholder="Employee Name" required>
                                    <span
                                    v-if="validation_error.hasOwnProperty('name')"
                                    class="text-danger"
                                    >
                                    {{ validation_error.name[0] }}
                                    </span>

                                </div>
                                <div class="col-6">
                                <label for="phoneNumber">Phone</label>
                                <input type="text" class="form-control" required v-model="employee.phoneNumber" id="phoneNumber" placeholder="Phone Number">
                                <span
                                    v-if="validation_error.hasOwnProperty('phoneNumber')"
                                    class="text-danger"
                                >
                                    {{ validation_error.phoneNumber[0] }}
                                </span>
                            </div>
                            <div class="col-6 mt-1">
                                <label for="emp_email">Email</label>
                                <input type="text" class="form-control" required v-model="employee.email" id="emp_email" placeholder="Employee Email">
                                <span
                                    v-if="validation_error.hasOwnProperty('email')"
                                    class="text-danger"
                                >
                                    {{ validation_error.email[0] }}
                                </span>
                            </div>

                            <div class="col-6 mt-1" v-if="employee_id == ''">
                                <label for="emp_passw">Password</label>
                                <input type="text" class="form-control" required v-model="employee.password" id="emp_passw" placeholder="Password">
                                <span
                                    v-if="validation_error.hasOwnProperty('password')"
                                    class="text-danger"
                                >
                                    {{ validation_error.password[0] }}
                                </span>
                            </div>
                            <div class="col-6 mt-1">
                            <label for="emp-role">Role</label>
                                <select class="form-control" v-model="employee.roleId" required>
                                    <option value="">Select Role</option>
                                    <option :value="role.id" v-for="(role,ind) in roles" :key="ind">{{ role.roleName }}</option>
                                </select>
                                <span
                                    v-if="validation_error.hasOwnProperty('roleId')"
                                    class="text-danger"
                                >
                                    {{ validation_error.roleId[0] }}
                                </span>
                            </div>
                            <div class="col-6 mt-1">
                            <label for="userType">User Type</label>
                                <select class="form-control" v-model="employee.userType" id="userType" required>
                                    <option value="">Select User Type</option>
                                    <option value="BUSINESS_MANAGER">BUSINESS MANAGER</option>
                                    <option value="LISTING_MANAGER">LISTING MANAGER</option>
                                </select>
                                <span
                                    v-if="validation_error.hasOwnProperty('userType')"
                                    class="text-danger"
                                >
                                    {{ validation_error.userType[0] }}
                                </span>
                            </div>
                        </div>

                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>

                                <button v-if="employee_id == ''" type="button" class="btn btn-primary" @click="storeEmp"><div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>Submit</button>

                                <button v-else type="button" class="btn btn-primary" @click="updateEmp"><div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>Update</button>
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
.loader-xl {
    width: 5rem;
    height: 5rem;
  }
</style>
