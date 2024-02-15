<script>
import Mixin from '../../mixer'
import { Bootstrap4Pagination } from 'laravel-vue-pagination';

export default {
    mixins: [Mixin],
    props: ['roles'],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            form: {
                name: '',
                email: '',
                password: '',
                role: ''
            },
            employee: [],
            keyword: '',
            employee_id: '',
            url: baseUrl,
            limit: 3,
            keepLength: false,
            validation_error : {}
        }
    },

    methods: {
        getEmployee(page = 1){
            axios.get(baseUrl+'employee/create?page='+page+'&keyword='+this.keyword).then(
                response => {
                    this.employee = response.data
                }
            ). catch(e => {
                console.log(e.response.data)
            })   
        },

        storeEmp(){
            try{
                axios.post(baseUrl+'employee',this.form).then(
                    response => {
                        this.successMessage(response.data)
                        $("#emplModal").modal('hide');
                        this.getEmployee()
                        this.formReset()
                    }
                ). catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors
                    } else {

                        this.validationError()
                    }
                })
            }catch(e){
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors
                }
            }
        },

        editEmp(emp){
            this.employee_id = emp.id
            this.form.name = emp.name
            this.form.email = emp.email
            this.form.role = emp.role_id
            $("#emplModal").modal('show');
        },

        deleteEmp(id){
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
                    axios.delete(baseUrl+`employee/${id}`).then(
                        response => {
                            this.successMessage(response.data)
                            $("#emplModal").modal('hide');
                            this.getEmployee()
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        updateEmp(){
            try{
                axios.put(baseUrl+'employee/'+this.employee_id,this.form).then(
                    response => {
                        this.successMessage(response.data)
                        $("#emplModal").modal('hide');
                        this.getEmployee()
                        this.formReset()
                    }
                ). catch(e => {
                    if(e.response.status == 422){
                        this.validation_error = e.response.data.errors
                    } else {
                        this.validationError()
                    }
                })
            }catch(e){
                this.validationError({'message':'Something went wrong!'})
            }
        },
        searchKeyword(){
            if(this.keyword.length < 3) return false;
            this.getEmployee()
        },

        formReset(){
            this.form = {
                name: '',
                email: '',
                password: '',
                role: ''
            }
            this.keyword = ''
            this.employee_id = ''
            this.validation_error = {}
        },
    },

    mounted(){
        this.getEmployee()
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
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                        <h4>Employee</h4>
                        <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('employee-create')" data-toggle="modal" data-target="#emplModal" @click="formReset">Add New</button>
                    </div>                          
                </div>
            </div>       
            <div class="widget-content widget-content-area">
                <div class="row"> 
                    <div class="col-4 d-flex justify-content-between mb-2">
                        <input id="search" placeholder="Search By Name" type="text" @keyup="searchKeyword()" class="form-control form-control-sm" v-model="keyword" />
                        <button class="btn btn-danger mx-2" @click="formReset()">Clear</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th v-if="showPermission.includes('employee-edit') || showPermission.includes('employee-delete')">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(empl,index) in employee.data" :key="index">
                                <tr>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ empl.name }}</td>
                                    <td>{{ empl.email }} </td>
                                    <td>{{ empl.role_id ? empl.role.role_name : 'No Role'}} </td>
                                    <td v-if="showPermission.includes('employee-edit') || showPermission.includes('employee-delete')">
                                        <button v-if="showPermission.includes('employee-edit')" type="button" class="btn btn-sm btn-info" @click="editEmp(empl)">Edit</button>
                                        <button type="button" v-if="showPermission.includes('employee-delete')" class="btn btn-sm btn-danger ml-2" @click="deleteEmp(empl.id)">Delete</button>
                                    </td>
                                </tr>					
                            </template>
                        </tbody>
                    </table>
                        <Bootstrap4Pagination
                            :data="employee"
                            :limit="limit"
                            :keep-length="keepLength"
                            @pagination-change-page="getEmployee"
                        />
                </div>

            </div>
        </div>
    </div>
    <div id="emplModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog">
            
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
                            <div class="form-group">
                                <label for="emp_name">Employee Name</label>
                                <input type="text" class="form-control" v-model="form.name" id="emp_name" placeholder="Employee Name">
                                <span
                                    v-if="validation_error.hasOwnProperty('name')"
                                    class="text-danger"
                                >
                                    {{ validation_error.name[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="emp_email">Email</label>
                                <input type="text" class="form-control" v-model="form.email" id="emp_email" placeholder="Employee Email">
                                <span
                                    v-if="validation_error.hasOwnProperty('email')"
                                    class="text-danger"
                                >
                                    {{ validation_error.email[0] }}
                                </span>
                            </div>

                            <div class="form-group" v-if="employee_id == ''">
                                <label for="emp_passw">Password</label>
                                <input type="text" class="form-control" v-model="form.password" id="emp_passw" placeholder="Password">
                                <span
                                    v-if="validation_error.hasOwnProperty('password')"
                                    class="text-danger"
                                >
                                    {{ validation_error.password[0] }}
                                </span>
                            </div>

                            <div class="form-group">
                            <label for="emp-role">Role</label>
                                <select class="form-control" v-model="form.role">
                                    <option value="">Select Role</option>
                                    <option :value="role.id" v-for="(role,ind) in roles" :key="ind">{{ role.role_name }}</option>
                                </select>
                                <span
                                    v-if="validation_error.hasOwnProperty('role')"
                                    class="text-danger"
                                >
                                    {{ validation_error.role[0] }}
                                </span>
                            </div>

                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>
        
                                <button v-if="employee_id == ''" type="button" class="btn btn-primary" @click="storeEmp">Submit</button>

                                <button v-else type="button" class="btn btn-primary" @click="updateEmp">Update</button>
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

</style>