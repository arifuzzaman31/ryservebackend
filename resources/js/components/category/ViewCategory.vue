<script>
import Mixin from '../../mixer'
import Multiselect from '@vueform/multiselect'
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
export default {
    mixins:[Mixin],
    props: ['prp_fabric'],
    components:{
        Bootstrap4Pagination,
        Multiselect
    },
    data(){
        return {
            categories: [],
            parentcat: [],
            form:{
                id:'',
                category_name: '',
                precedence: '',
                parent_category: 0,
                whats_new: 1,
                status: 1
            },
            category_id: '',
            comp_form: {
                cat_id: '',
                composition: []
            },
            keyword: '',
            url: baseUrl,
            validation_error: {}
        }
    },
    methods: {
        getCategory(page = 1){
            try{
                axios.get(baseUrl+`get-category?page=${page}&per_page=10&keyword=${this.keyword}`).then(
                    response => {
                        this.categories = response.data
                    }
                ). catch(e => {
                   console.log(e.response.data)
                })
            }catch(e){
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors
                }
            }
        },

        getParentCat(){
            axios.get(baseUrl+`get-category?no_paginate=yes&parent_category=yes`).then(
                response => {
                    this.parentcat = response.data
                }
            ). catch(error => {
                console.log(error)
            })
        },

        storeCategory(){
            try{
                axios.post('category',this.form).then(
                    response => {
                        this.formReset()
                        this.getCategory()
                        this.getParentCat()
                        this.successMessage(response.data)
                        $("#cateModal").modal('hide');
                    }
                ). catch(e => {
                   console.log(e.response.data)
                })
            }catch(e){
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors
                }
            }
        },

        deleteMenu(id){
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
                    axios.delete(baseUrl+`category/${id}`).then(
                        response => {
                            this.getCategory()
                            this.getParentCat()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        addFabricToCat(item){
            this.comp_form.cat_id = item.id
            this.comp_form.composition = item.composition.map(itm => itm.id)
            $("#catFabricModal").modal('show');
        },

        updateCompCat(){
            try{
                axios.post('fabric-add-category',this.comp_form).then(
                    response => {
                        this.successMessage(response.data)
                        $("#catFabricModal").modal('hide');
                        this.formReset()
                        this.getParentCat()
                        this.getCategory()
                    }
                ). catch(e => {
                   console.log(e.response.data)
                })
            }catch(e){
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors
                }
            }
        },

        renameCate(item){
            this.category_id = item.id
            this.form.id = item.id
            this.form.category_name = item.category_name
            this.form.parent_category = item.parent_category
            this.form.whats_new = item.whats_new
            this.form.status = item.status == 1 ? true : false
            $("#cateModal").modal('show');
        },

        searchKeyword(){
            if(this.keyword.length < 3) return false;
            this.getCategory()
        },

        updateCategory(){
            try{
                axios.post('edit-category',this.form).then(
                    response => {
                        this.successMessage(response.data)
                        $("#cateModal").modal('hide');
                        this.formReset()
                        this.getParentCat()
                        this.getCategory()
                    }
                ). catch(e => {
                   console.log(e.response.data)
                })
            }catch(e){
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors
                }
            }
        },

        formReset(){
            this.validation_error = {}
            this.category_id = '';
            this.keyword = '';
            this.form = {
                id:'',
                category_name : '',
                precedence : '',
                parent_category: 0,
                whats_new: 0,
                status : 1
            }
            this.comp_form = {
                cat_id: '',
                composition: []
            }
        }
    },
    mounted(){
        this.getCategory()
        this.getParentCat()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>

<template>
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row" style="width:99%">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between mx-3">
                    <h4>Category</h4>
                    <button class="btn btn-primary mb-2 mr-3" v-if="showPermission.includes('menu-create')" data-toggle="modal" data-target="#cateModal" @click="formReset">Add New</button>
                </div>
            </div>
        </div>
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox">
            <div class="widget-content widget-content-area">
                <div class="col-4 mb-2 d-flex justify-content-between">
                    <input id="search" placeholder="Search By Category Name" @keyup="searchKeyword()"  v-model="keyword" type="text" class="form-control form-control-sm" />
                    <button class="btn btn-danger mx-2" @click="() => {this.keyword = ''; getCategory()}">Clear</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-4">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Category</th>
                                <th>Parent Category</th>
                                <th class="text-center">Precedence</th>
                                <th class="text-center">Whats New</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" v-if="showPermission.includes('menu-edit')">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="categories.data && categories.data.length > 0">
                            <tr v-for="(cat,ind) in categories.data" :key="ind">
                                <td>{{ ++ind }}</td>
                                <td>{{ cat.category_name }}</td>
                                <td>{{ cat.parent_category == 0 ? 'Main Category' : cat.subcategory.category_name }}</td>
                                <td>{{ cat.precedence }}</td>
                                <td class="text-center">{{ cat.whats_new == 1 ? 'Enable' : 'Disable' }}</td>
                                <td class="text-center">{{ cat.status == 1 ? 'Active' : 'Deactive' }}</td>
                                <td class="text-center" v-if="showPermission.includes('menu-edit') || showPermission.includes('menu-delete')">
                                   <a v-if="showPermission.includes('menu-edit')" class="btn btn-warning btn-sm" target="_blank" :href="url+'category/'+cat.id+'/edit'">Add Image</a>
                                   <a v-if="showPermission.includes('menu-edit')" class="btn btn-info mx-1 btn-sm" @click="renameCate(cat)">Rename</a>
                                   <a v-if="showPermission.includes('menu-delete')" class="btn btn-danger btn-sm mx-1" @click="deleteMenu(cat.id)">Delete</a>
                                   <a class="btn btn-success btn-sm" @click="addFabricToCat(cat)">Composition</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <Bootstrap4Pagination
                        :data="categories"
                        @pagination-change-page="getCategory"
                    />
                </div>

            </div>
        </div>
    </div>
    <div id="cateModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="category_id == ''">Create Category</h5>
                    <h5 class="modal-title" v-else>Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="widget-content widget-content-area">
                        <form>
                            <div class="form-group">
                                <label for="category_name">Category</label>
                                <input type="text" class="form-control" v-model="form.category_name" id="tax_name" placeholder="Category Name">
                                <span
                                    v-if="validation_error.hasOwnProperty('category_name')"
                                    class="text-danger"
                                >
                                    {{ validation_error.category_name[0] }}
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="category_name">Parent Category</label>
                                <select id="product-category" class="form-control" v-model="form.parent_category">
                                    <option value="0">Choose Parent Category</option>
                                    <option v-for="(value,index) in parentcat" :value="value.id" :key="index">{{ value.category_name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="category_name">Whats New</label>
                                <select id="product-category" class="form-control" v-model="form.whats_new">
                                    <option value="0">Disable</option>
                                    <option value="1">Enable</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="precedence">Precedence</label>
                                <input type="number" class="form-control" v-model="form.precedence" id="precedence" placeholder="Precedence">
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <label for="siz-status">Status</label>
                                    <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                        <input v-model="form.status" type="checkbox" :checked="form.status" id="siz-status">
                                        <span class="slider round"></span>
                                    </label>
                            </div>

                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click.prevent="formReset()"></i> Discard</button>

                                <button v-if="category_id == ''" type="button" class="btn btn-primary" @click.prevent="storeCategory()">Submit</button>

                                <button v-else type="button" class="btn btn-primary" @click.prevent="updateCategory()">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="catFabricModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Composition Add To Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="widget-content widget-content-area">
                        <form>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="product-Fabric">Composition</label>
                                    <Multiselect
                                        v-model="comp_form.composition"
                                        placeholder="Select Fabric"
                                        track-by="name"
                                        label="name"
                                        mode="tags"
                                        :close-on-select="false"
                                        :search="true"
                                        :options="prp_fabric"
                                        :searchable="true"
                                        >
                                        <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                        <div
                                            class="multiselect-tag is-user"
                                            :class="{
                                            'is-disabled': disabled
                                            }"
                                        >
                                            {{ option.name }}
                                            <span
                                            v-if="!disabled"
                                            class="multiselect-tag-remove"
                                            @mousedown.prevent="handleTagRemove(option, $event)"
                                            >
                                            <span class="multiselect-tag-remove-icon"></span>
                                            </span>
                                        </div>
                                        </template>
                                    </Multiselect>
                                </div>
                            </div>

                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click.prevent="formReset()"></i> Discard</button>

                                <button type="button" class="btn btn-primary" @click="updateCompCat()">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>
<style src="@vueform/multiselect/themes/default.css"></style>

<style scoped>

</style>
