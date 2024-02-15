<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import Mixin from '../../mixer';

export default {
    props:['section'],
    mixins:[Mixin],
    components:{
        Bootstrap4Pagination
    },
    data(){
        return {
            allproduct: [],
            keyword: '',
            filterdata: {
                per_page: 10
            },
            isCheckAll: false,
            addTocamp: {
                campaign: '',
                product:[],
            },

            validation_error: {},
            file: null,
            limit: 3,
            keepLength: false,
            url: baseUrl
        }
    },
    methods: {
        async getProduct(page = 1) {
            try{
                await axios.get(baseUrl+`get-product?page=${page}&per_page=${this.filterdata.per_page}&section_id=${this.section.id}&keyword=${this.keyword}`)
                .then(response => {
                    this.allproduct = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },

        searchProduct(){
            if(this.keyword.length < 3) return ;
            this.getProduct()
        },

        filterClear(){
            this.filterdata = {
                per_page: 10
            },
            this.keyword= ''
            this.getProduct()
        },
        singleRemove(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "Product won't be able to this Section!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(baseUrl+`remove-product-section`,{'section_id':this.section.id,'product_id':[id]}).then(
                        response => {
                            this.getProduct()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        deleteProduct(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Product won't be able to this campaign!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(baseUrl+`remove-product-section`,{'section_id':id,'product_id':this.addTocamp.product}).then(
                        response => {
                            this.getProduct()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },
        removeFromSection(){
            if(this.addTocamp.product.length < 1){
                alert('Please, Select Product');
                return ;
            }
            this.deleteProduct(this.section.id)
        },

        selectAll(){
            this.isCheckAll = !this.isCheckAll;
            this.addTocamp.product = [];
            if(this.isCheckAll){ // Check all
                const ids = this.allproduct.data.map(v => v.id)
                this.addTocamp.product = ids
            }
        },
    },
    mounted(){
        this.getProduct()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>
<style scoped>
.hide {
    visibility: hidden;
}
</style>
<template>
    <div class="container-fluid mt-3">
        <div class="statbox widget box box-shadow mb-4">
                <div class="row">
                    <div class="col-md-2 col-lg-1 col-4 mb-3">
                        <label for="search">Per-Page</label>
                        <select id="product-perpage" class="form-control" @change="getProduct()" v-model="filterdata.per_page">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3 col-12 mb-3">
                        <label for="search">Search</label>
                        <input type="text" @keyup="searchProduct()" v-model="keyword" class="form-control" id="search" placeholder="Search by Name" >
                    </div>

                    <div class="col-md-4 col-lg-3 col-12 mt-4">
                        <button type="button" class="btn btn-danger btn-sm" @click="filterClear()">CLEAR</button>
                        <button type="button" class="btn btn-success btn-sm ml-2" @click="removeFromSection()">Remove From Section</button>
                    </div>
                </div>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4" v-if="allproduct.data && allproduct.data.length > 0" >
                <thead>
                    <tr>
                        <th class="checkbox-column">
                            <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                <input type="checkbox" @click="selectAll()" v-model="isCheckAll" class="new-control-input todochkbox" id="todoAll">
                                <span class="new-control-indicator"></span>
                            </label>
                        </th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(product,index) in allproduct.data" :key="index" >
                        <tr>
                            <td class="checkbox-column">
                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                    <input type="checkbox" multiple :name="product.product_name" v-model="addTocamp.product" :value="product.id" class="new-control-input todochkbox" id="todo-1">
                                    <span class="new-control-indicator"></span>
                                </label>
                            </td>
                            <td>
                                <p class="mb-0">{{ product.product_name }}</p>
                            </td>
                            <td>{{ product.category.category_name }}</td>
                            <td>{{ product.subcategory.category_name }}</td>
                            <td class="text-center">
                                <span class="badge shadow-none" :class="product.status == 1 ? 'outline-badge-info':'outline-badge-danger'">{{ product.status == 1 ? 'Active' : 'Deactive' }}</span>
                            </td>
                            <td class="d-flex justify-content-around" >
                                <a type="button" title="Remove" @click="singleRemove(product.id)" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div v-else class="text-center">
                <h4>No Product Found</h4>
            </div>
        </div>
    <Bootstrap4Pagination
        :data="allproduct"
        @pagination-change-page="getProduct"
        :limit="limit"
        :keep-length="keepLength"
    />
</div>
</template>
